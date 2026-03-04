<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* C:\OSPanel\domains\inventpro-test\themes\invent-pro\partials\doc-builder\modals.htm */
class __TwigTemplate_61b8e4e42dc4f5d862cc8a3024fac4e0 extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->extensions[SandboxExtension::class];
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 1
        yield "<template id=\"doc-builder-warning-modal-template\">
    <div class=\"settings-modal\">
        <div id=\"doc-builder-warning-missing-section\" class=\"settings-modal__confirm-text\">
            <div id=\"doc-builder-warning-missing-lead\"></div>
            <ul id=\"doc-builder-warning-missing-list\"></ul>
            <div id=\"doc-builder-warning-missing-help\"></div>
        </div>

        <div id=\"doc-builder-warning-common-text\" class=\"settings-modal__confirm-text\"></div>

        <div class=\"settings-modal__actions\">
            <button type=\"button\" id=\"doc-builder-warning-cancel\" class=\"button button--nm button--secondary\">Відмінити</button>
            <button type=\"button\" id=\"doc-builder-warning-settings\" class=\"button button--nm button--secondary\">В налаштування</button>
            <button type=\"button\" id=\"doc-builder-warning-generate\" class=\"button button--nm button--brand\">Сформувати</button>
        </div>
    </div>
</template>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\inventpro-test\\themes\\invent-pro\\partials\\doc-builder\\modals.htm";
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<template id=\"doc-builder-warning-modal-template\">
    <div class=\"settings-modal\">
        <div id=\"doc-builder-warning-missing-section\" class=\"settings-modal__confirm-text\">
            <div id=\"doc-builder-warning-missing-lead\"></div>
            <ul id=\"doc-builder-warning-missing-list\"></ul>
            <div id=\"doc-builder-warning-missing-help\"></div>
        </div>

        <div id=\"doc-builder-warning-common-text\" class=\"settings-modal__confirm-text\"></div>

        <div class=\"settings-modal__actions\">
            <button type=\"button\" id=\"doc-builder-warning-cancel\" class=\"button button--nm button--secondary\">Відмінити</button>
            <button type=\"button\" id=\"doc-builder-warning-settings\" class=\"button button--nm button--secondary\">В налаштування</button>
            <button type=\"button\" id=\"doc-builder-warning-generate\" class=\"button button--nm button--brand\">Сформувати</button>
        </div>
    </div>
</template>", "C:\\OSPanel\\domains\\inventpro-test\\themes\\invent-pro\\partials\\doc-builder\\modals.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = [];
        static $filters = [];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                [],
                [],
                [],
                $this->source
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->source);

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }
}
