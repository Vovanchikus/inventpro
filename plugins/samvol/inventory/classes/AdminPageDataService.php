<?php namespace Samvol\Inventory\Classes;

use Auth;
use Samvol\Inventory\Models\Operation;

class AdminPageDataService
{
    protected const ROLE_KEYS = [
        'receiver_name',
        'commission_head',
        'commission_member_1',
        'commission_member_2',
        'commission_member_3',
        'responsible_person',
    ];

    public function resolveCurrentUser()
    {
        try {
            return Auth::getUser();
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function hasAdminAccess($user): bool
    {
        if (!$user) {
            return false;
        }

        try {
            return OrganizationAccess::isOrganizationAdmin($user)
                || OrganizationAccess::isProjectAdmin($user);
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function buildSettingsPageData(): array
    {
        $user = $this->resolveCurrentUser();
        $isAdmin = $this->hasAdminAccess($user);

        $settings = [];
        if ($isAdmin && $user) {
            try {
                $scopeKey = SettingsScopeResolver::resolveScopeKey($user);
                $settings = DocumentTemplateSettings::get($scopeKey);
            } catch (\Throwable $e) {
                $settings = [];
            }
        }

        return [
            'isAdmin' => $isAdmin,
            'docTemplateSettings' => $settings,
            'docTemplateRoles' => (array) ($settings['roles'] ?? []),
        ];
    }

    public function buildDocumentBuilderData(int $operationId): array
    {
        $user = $this->resolveCurrentUser();
        $isAdmin = $this->hasAdminAccess($user);

        $docNames = [];
        $roleOptions = $this->emptyRoleMap([]);
        $defaults = $this->emptyRoleMap('');
        $defaultLabels = $this->emptyRoleMap('');
        $settings = [];
        $warnings = [
            'hasMissingSettings' => false,
            'missingItems' => [],
        ];

        if ($isAdmin && $operationId > 0) {
            $docNames = $this->resolveOperationDocumentNames($operationId);
        }

        if ($isAdmin && $user) {
            try {
                $scopeKey = SettingsScopeResolver::resolveScopeKey($user);
                $settings = DocumentTemplateSettings::get($scopeKey);
                [$roleOptions, $defaults, $defaultLabels] = $this->resolveBuilderRoleData((array) $settings);
                $warnings = $this->resolveBuilderWarnings((array) $settings);
            } catch (\Throwable $e) {
            }
        }

        return [
            'isAdmin' => $isAdmin,
            'builderOperationId' => $operationId,
            'builderDocNames' => $docNames,
            'builderRoleOptions' => $roleOptions,
            'builderDefaults' => $defaults,
            'builderDefaultLabels' => $defaultLabels,
            'builderWarnings' => $warnings,
            'builderCanGenerate' => $isAdmin && $operationId > 0 && !empty($docNames),
        ];
    }

    protected function resolveBuilderWarnings(array $settings): array
    {
        $missingItems = [];

        $requiredFields = [
            'edrpou' => 'ЄДРПОУ організації',
            'commission_order_details' => 'Дата та № наказу призначення комісії',
            'document_year' => 'Рік документа',
        ];

        foreach ($requiredFields as $key => $label) {
            $value = trim((string) ($settings[$key] ?? ''));
            if ($value === '') {
                $missingItems[] = $label;
            }
        }

        $roles = (array) ($settings['roles'] ?? []);
        $roleLabels = [
            'receiver_name' => 'На кого документ',
            'commission_head' => 'Голова комісії',
            'commission_member_1' => 'Член комісії №1',
            'commission_member_2' => 'Член комісії №2',
            'commission_member_3' => 'Член комісії №3',
            'responsible_person' => 'Матеріально-відповідальна особа',
        ];

        foreach ($roleLabels as $roleKey => $label) {
            $roleData = (array) ($roles[$roleKey] ?? []);
            $people = is_array($roleData['people'] ?? null) ? $roleData['people'] : [];
            if (count($people) === 0) {
                $missingItems[] = $label . ' (не додано жодної персони)';
            }
        }

        return [
            'hasMissingSettings' => !empty($missingItems),
            'missingItems' => array_values(array_unique($missingItems)),
        ];
    }

    protected function resolveOperationDocumentNames(int $operationId): array
    {
        try {
            $operation = Operation::with(['documents'])->find($operationId);
            if (!$operation || !$operation->documents) {
                return [];
            }

            $docs = [];
            foreach ($operation->documents as $document) {
                $name = trim((string) ($document->doc_name ?? ''));
                if ($name !== '') {
                    $docs[$name] = $name;
                }
            }

            return array_values($docs);
        } catch (\Throwable $e) {
            return [];
        }
    }

    protected function resolveBuilderRoleData(array $settings): array
    {
        $roles = (array) ($settings['roles'] ?? []);
        $roleOptions = [];
        $defaults = [];
        $defaultLabels = [];

        foreach (self::ROLE_KEYS as $roleKey) {
            $roleData = (array) ($roles[$roleKey] ?? []);
            $people = is_array($roleData['people'] ?? null) ? $roleData['people'] : [];
            $selectedId = trim((string) ($roleData['selected_id'] ?? ''));

            $options = [];
            $usedNames = [];
            $selectedName = '';
            $selectedLabel = '';

            foreach ($people as $person) {
                $name = trim((string) ($person['name'] ?? ''));
                $position = trim((string) ($person['position'] ?? ''));
                if ($name === '') {
                    continue;
                }

                if (!in_array($name, $usedNames, true)) {
                    $usedNames[] = $name;
                    $options[] = [
                        'value' => $name,
                        'name' => $name,
                        'position' => $position,
                        'label' => $this->composePersonLabel($name, $position),
                    ];
                }

                if ($selectedId !== '' && (string) ($person['id'] ?? '') === $selectedId) {
                    $selectedName = $name;
                    $selectedLabel = $this->composePersonLabel($name, $position);
                }
            }

            if ($selectedName === '' && !empty($options)) {
                $selectedName = (string) ($options[0]['value'] ?? '');
                $selectedLabel = (string) ($options[0]['label'] ?? '');
            }

            $roleOptions[$roleKey] = $options;
            $defaults[$roleKey] = $selectedName;
            $defaultLabels[$roleKey] = $selectedLabel;
        }

        return [$roleOptions, $defaults, $defaultLabels];
    }

    protected function composePersonLabel(string $name, string $position): string
    {
        $name = trim($name);
        $position = trim($position);

        if ($position !== '' && $name !== '') {
            return $position . ' — ' . $name;
        }

        return $name;
    }

    protected function emptyRoleMap($value): array
    {
        $data = [];
        foreach (self::ROLE_KEYS as $roleKey) {
            $data[$roleKey] = $value;
        }

        return $data;
    }
}
