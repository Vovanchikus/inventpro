<?php namespace Samvol\Inventory\Classes;

use InvalidArgumentException;
use Samvol\Inventory\Models\DocTemplatePerson;
use Samvol\Inventory\Models\DocTemplateSetting;

class DocumentTemplateSettings
{
    private const FIELD_KEYS = [
        'edrpou',
        'document_year',
        'commission_order_details',
    ];

    private const ROLE_KEYS = [
        'receiver_name',
        'commission_head',
        'commission_member_1',
        'commission_member_2',
        'commission_member_3',
        'responsible_person',
    ];

    public static function fieldKeys(): array
    {
        return self::FIELD_KEYS;
    }

    public static function roleKeys(): array
    {
        return self::ROLE_KEYS;
    }

    public static function get(string $scopeKey = 'global'): array
    {
        self::ensureInitialized($scopeKey);

        $settingsMap = self::settingsMap($scopeKey);
        $defaults = self::defaults();

        $result = [
            'edrpou' => trim((string)($settingsMap['edrpou'] ?? $defaults['edrpou'])),
            'document_year' => trim((string)($settingsMap['document_year'] ?? $defaults['document_year'])),
            'commission_order_details' => trim((string)($settingsMap['commission_order_details'] ?? $defaults['commission_order_details'])),
            'roles' => [],
        ];

        if ($result['document_year'] === '') {
            $result['document_year'] = (string)date('Y');
        }

        foreach (self::ROLE_KEYS as $roleKey) {
            $peopleRows = DocTemplatePerson::query()
                ->where('scope_key', $scopeKey)
                ->where('role_key', $roleKey)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();

            $people = $peopleRows->map(function ($row) {
                return [
                    'id' => (string)$row->id,
                    'name' => trim((string)$row->name),
                    'position' => trim((string)($row->position ?? '')),
                ];
            })->filter(function ($person) {
                return $person['name'] !== '';
            })->values()->all();

            if (empty($people)) {
                self::seedRolePeople($scopeKey, $roleKey, $defaults['roles'][$roleKey]['people']);
                $peopleRows = DocTemplatePerson::query()
                    ->where('scope_key', $scopeKey)
                    ->where('role_key', $roleKey)
                    ->orderBy('sort_order')
                    ->orderBy('id')
                    ->get();

                $people = $peopleRows->map(function ($row) {
                    return [
                        'id' => (string)$row->id,
                        'name' => trim((string)$row->name),
                        'position' => trim((string)($row->position ?? '')),
                    ];
                })->values()->all();
            }

            $selectedKey = self::selectedSettingKey($roleKey);
            $selectedId = trim((string)($settingsMap[$selectedKey] ?? ''));

            $exists = false;
            foreach ($people as $person) {
                if ((string)$person['id'] === $selectedId) {
                    $exists = true;
                    break;
                }
            }

            if (!$exists) {
                $selectedId = (string)($people[0]['id'] ?? '');
                self::upsertSetting($scopeKey, $selectedKey, $selectedId);
            }

            $result['roles'][$roleKey] = [
                'people' => $people,
                'selected_id' => $selectedId,
            ];
        }

        return $result;
    }

    public static function saveField(string $key, $value, string $scopeKey = 'global'): array
    {
        if (!in_array($key, self::FIELD_KEYS, true)) {
            throw new InvalidArgumentException('Невідоме поле для збереження');
        }

        if ($key === 'document_year') {
            $normalizedYear = preg_replace('/\D+/', '', (string)$value);
            $value = $normalizedYear !== '' ? substr($normalizedYear, 0, 4) : (string)date('Y');
        } else {
            $value = trim((string)$value);
        }

        self::upsertSetting($scopeKey, $key, (string)$value);

        return self::get($scopeKey);
    }

    public static function addPerson(string $roleKey, string $name, string $position = '', string $scopeKey = 'global'): array
    {
        self::assertRole($roleKey);

        $name = trim($name);
        if ($name === '') {
            throw new InvalidArgumentException('Ім’я не може бути порожнім');
        }

        $nextSort = (int)DocTemplatePerson::query()
            ->where('scope_key', $scopeKey)
            ->where('role_key', $roleKey)
            ->max('sort_order');

        $person = new DocTemplatePerson();
        $person->organization_id = self::organizationIdFromScopeKey($scopeKey);
        $person->scope_key = $scopeKey;
        $person->role_key = $roleKey;
        $person->name = $name;
        $person->position = trim($position);
        $person->sort_order = $nextSort + 1;
        $person->save();

        $selectedKey = self::selectedSettingKey($roleKey);
        $selectedValue = self::settingValue($scopeKey, $selectedKey);
        if (trim((string)$selectedValue) === '') {
            self::upsertSetting($scopeKey, $selectedKey, (string)$person->id);
        }

        return self::get($scopeKey);
    }

    public static function updatePerson(string $roleKey, string $personId, string $name, string $position = '', string $scopeKey = 'global'): array
    {
        self::assertRole($roleKey);

        $name = trim($name);
        if ($name === '') {
            throw new InvalidArgumentException('Ім’я не може бути порожнім');
        }

        $person = DocTemplatePerson::query()
            ->where('scope_key', $scopeKey)
            ->where('role_key', $roleKey)
            ->where('id', (int)$personId)
            ->first();

        if (!$person) {
            throw new InvalidArgumentException('Картку не знайдено');
        }

        $person->name = $name;
        $person->position = trim($position);
        $person->save();

        return self::get($scopeKey);
    }

    public static function deletePerson(string $roleKey, string $personId, string $scopeKey = 'global'): array
    {
        self::assertRole($roleKey);

        $peopleQuery = DocTemplatePerson::query()
            ->where('scope_key', $scopeKey)
            ->where('role_key', $roleKey);

        $totalCount = (int)$peopleQuery->count();
        if ($totalCount <= 1) {
            throw new InvalidArgumentException('Має залишитись хоча б одна картка');
        }

        $person = (clone $peopleQuery)->where('id', (int)$personId)->first();
        if (!$person) {
            throw new InvalidArgumentException('Картку не знайдено');
        }

        $person->delete();

        $selectedKey = self::selectedSettingKey($roleKey);
        $selectedValue = trim((string)self::settingValue($scopeKey, $selectedKey));
        if ($selectedValue === (string)$personId) {
            $next = DocTemplatePerson::query()
                ->where('scope_key', $scopeKey)
                ->where('role_key', $roleKey)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->first();

            self::upsertSetting($scopeKey, $selectedKey, (string)($next->id ?? ''));
        }

        return self::get($scopeKey);
    }

    public static function selectPerson(string $roleKey, string $personId, string $scopeKey = 'global'): array
    {
        self::assertRole($roleKey);

        $exists = DocTemplatePerson::query()
            ->where('scope_key', $scopeKey)
            ->where('role_key', $roleKey)
            ->where('id', (int)$personId)
            ->exists();

        if (!$exists) {
            throw new InvalidArgumentException('Картку не знайдено');
        }

        self::upsertSetting($scopeKey, self::selectedSettingKey($roleKey), (string)$personId);

        return self::get($scopeKey);
    }

    public static function extractDocDefaults(string $scopeKey = 'global'): array
    {
        $settings = self::get($scopeKey);
        $defaults = [];

        foreach (self::ROLE_KEYS as $roleKey) {
            $selected = self::selectedPersonForRole($settings, $roleKey);
            $defaults[$roleKey] = trim((string)($selected['name'] ?? ''));
        }

        return $defaults;
    }

    private static function ensureInitialized(string $scopeKey): void
    {
        $hasSettings = DocTemplateSetting::query()
            ->where('scope_key', $scopeKey)
            ->exists();
        $hasPeople = DocTemplatePerson::query()
            ->where('scope_key', $scopeKey)
            ->exists();

        if ($hasSettings || $hasPeople) {
            return;
        }

        $defaults = self::defaults();

        self::upsertSetting($scopeKey, 'edrpou', (string)$defaults['edrpou']);
        self::upsertSetting($scopeKey, 'document_year', (string)$defaults['document_year']);
        self::upsertSetting($scopeKey, 'commission_order_details', (string)$defaults['commission_order_details']);

        foreach (self::ROLE_KEYS as $roleKey) {
            self::seedRolePeople($scopeKey, $roleKey, $defaults['roles'][$roleKey]['people']);

            $first = DocTemplatePerson::query()
                ->where('scope_key', $scopeKey)
                ->where('role_key', $roleKey)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->first();

            self::upsertSetting($scopeKey, self::selectedSettingKey($roleKey), (string)($first->id ?? ''));
        }
    }

    private static function settingsMap(string $scopeKey): array
    {
        return DocTemplateSetting::query()
            ->where('scope_key', $scopeKey)
            ->get()
            ->mapWithKeys(function ($row) {
                return [$row->key => (string)($row->value ?? '')];
            })
            ->all();
    }

    private static function upsertSetting(string $scopeKey, string $key, string $value): void
    {
        $record = DocTemplateSetting::query()
            ->where('scope_key', $scopeKey)
            ->where('key', $key)
            ->first();

        if (!$record) {
            $record = new DocTemplateSetting();
            $record->organization_id = self::organizationIdFromScopeKey($scopeKey);
            $record->scope_key = $scopeKey;
            $record->key = $key;
        }

        $record->value = $value;
        $record->save();
    }

    private static function settingValue(string $scopeKey, string $key): string
    {
        $row = DocTemplateSetting::query()
            ->where('scope_key', $scopeKey)
            ->where('key', $key)
            ->first();

        return trim((string)($row->value ?? ''));
    }

    private static function seedRolePeople(string $scopeKey, string $roleKey, array $people): void
    {
        $sort = 1;
        foreach ($people as $person) {
            $name = trim((string)($person['name'] ?? ''));
            if ($name === '') {
                continue;
            }

            $row = new DocTemplatePerson();
            $row->organization_id = self::organizationIdFromScopeKey($scopeKey);
            $row->scope_key = $scopeKey;
            $row->role_key = $roleKey;
            $row->name = $name;
            $row->position = trim((string)($person['position'] ?? ''));
            $row->sort_order = $sort++;
            $row->save();
        }
    }

    private static function selectedSettingKey(string $roleKey): string
    {
        return 'selected.' . $roleKey;
    }

    private static function organizationIdFromScopeKey(string $scopeKey): ?int
    {
        if (preg_match('/^org:(\d+)$/', $scopeKey, $match) !== 1) {
            return null;
        }

        $organizationId = (int) ($match[1] ?? 0);

        return $organizationId > 0 ? $organizationId : null;
    }

    private static function selectedPersonForRole(array $settings, string $roleKey): ?array
    {
        $roleData = $settings['roles'][$roleKey] ?? null;
        if (!is_array($roleData)) {
            return null;
        }

        $people = is_array($roleData['people'] ?? null) ? $roleData['people'] : [];
        $selectedId = (string)($roleData['selected_id'] ?? '');

        foreach ($people as $person) {
            if ((string)($person['id'] ?? '') === $selectedId) {
                return $person;
            }
        }

        return $people[0] ?? null;
    }

    private static function defaults(): array
    {
        return [
            'edrpou' => '',
            'document_year' => (string)date('Y'),
            'commission_order_details' => '',
            'roles' => [
                'receiver_name' => [
                    'people' => [
                        [
                            'name' => 'Юрій ЯВТУШЕНКО',
                            'position' => 'Начальник 6 ДПРЗ ГУ ДСНС України у Харківській області',
                        ],
                        [
                            'name' => 'Андрій МИХАЙЛОВ',
                            'position' => 'Тимчасово виконуючий обов’язки начальника 6 ДПРЗ ГУ ДСНС України у Харківській області',
                        ],
                    ],
                ],
                'commission_head' => [
                    'people' => [
                        [
                            'name' => 'Андрій МИХАЙЛОВ',
                            'position' => 'Тимчасово виконуючий обов’язки начальника 6 ДПРЗ ГУ ДСНС України у Харківській області',
                        ],
                    ],
                ],
                'commission_member_1' => [
                    'people' => [
                        [
                            'name' => 'Олександр БОНДАРЕНКО',
                            'position' => 'Начальник відділення ресурсного забезпечення 6 ДПРЗ ГУ ДСНС України у Харківській області',
                        ],
                        [
                            'name' => 'Сергій ЄРМІЛІН',
                            'position' => 'Тимчасово виконуючий обов’язки начальника відділення ресурсного забезпечення 6 ДПРЗ ГУ ДСНС України у Харківській області',
                        ],
                    ],
                ],
                'commission_member_2' => [
                    'people' => [
                        [
                            'name' => 'Микола ТИТАРЕНКО',
                            'position' => 'Фахівець відділення ресурсного забезпечення 6 ДПРЗ ГУ ДСНС України у Харківській області',
                        ],
                    ],
                ],
                'commission_member_3' => [
                    'people' => [
                        [
                            'name' => 'Євгеній БОКАНЬ',
                            'position' => 'Фахівець відділення організації реагування на НС 6 ДПРЗ ГУ ДСНС України у Харківській області',
                        ],
                    ],
                ],
                'responsible_person' => [
                    'people' => [
                        [
                            'name' => 'Дмитро НЕСЛОВ',
                            'position' => 'Заступник начальника 36 ДПРЧ 6 ДПРЗ ГУ ДСНС України у Харківській області',
                        ],
                    ],
                ],
            ],
        ];
    }

    private static function assertRole(string $roleKey): void
    {
        if (!in_array($roleKey, self::ROLE_KEYS, true)) {
            throw new InvalidArgumentException('Невідома роль');
        }
    }
}
