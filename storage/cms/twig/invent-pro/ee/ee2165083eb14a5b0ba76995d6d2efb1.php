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

/* C:\OSPanel\domains\inventpro\themes\invent-pro\pages\login.htm */
class __TwigTemplate_c3cfc0fe525f7b1e07beb68af03ece98 extends Template
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
        yield "<div class=\"container--extra-sm box--light\">
    <div class=\"form\">
        <div class=\"form__title\">
            <h1>Вхід</h1>
            <p>Тільки для персоналу 38 ДПРП</p>
        </div>
        ";
        // line 7
        $context['__cms_component_params'] = [];
        echo $this->env->getExtension('Cms\Twig\Extension')->componentFunction("account::signin"        , $context['__cms_component_params']        );
        unset($context['__cms_component_params']);
        // line 8
        yield "    </div>
</div>



<h2>Реєстрація в системі</h2>
";
        // line 14
        $context['__cms_component_params'] = [];
        echo $this->env->getExtension('Cms\Twig\Extension')->componentFunction("account::register"        , $context['__cms_component_params']        );
        unset($context['__cms_component_params']);
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\inventpro\\themes\\invent-pro\\pages\\login.htm";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  64 => 14,  56 => 8,  52 => 7,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<div class=\"container--extra-sm box--light\">
    <div class=\"form\">
        <div class=\"form__title\">
            <h1>Вхід</h1>
            <p>Тільки для персоналу 38 ДПРП</p>
        </div>
        {% component 'account::signin' %}
    </div>
</div>



<h2>Реєстрація в системі</h2>
{% component 'account::register' %}", "C:\\OSPanel\\domains\\inventpro\\themes\\invent-pro\\pages\\login.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["component" => 7];
        static $filters = [];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['component'],
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
