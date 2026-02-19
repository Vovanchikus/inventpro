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

/* C:\OSPanel\domains\inventpro-test\themes\invent-pro\pages\operation-history.htm */
class __TwigTemplate_8d6ecdc6e36fab3314dfc62dd3e61353 extends Template
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
        $context['__cms_partial_params'] = [];
        echo $this->env->getExtension('Cms\Twig\Extension')->partialFunction("global/main-box"        , $context['__cms_partial_params']        , true        );
        unset($context['__cms_partial_params']);
        // line 2
        yield "
<!-- Таблица истории операций -->
<div class=\"table-outter\">

  <div class=\"operation-history-table table\">
    <div id=\"product-list\">
      ";
        // line 8
        $context['__cms_partial_params'] = [];
        $context['__cms_partial_params']['histories'] = ($context["histories"] ?? null)        ;
        echo $this->env->getExtension('Cms\Twig\Extension')->partialFunction("operation-history/history-item"        , $context['__cms_partial_params']        , true        );
        unset($context['__cms_partial_params']);
        // line 9
        yield "    </div>
  </div>
</div>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\inventpro-test\\themes\\invent-pro\\pages\\operation-history.htm";
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
        return array (  61 => 9,  56 => 8,  48 => 2,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% partial 'global/main-box' %}

<!-- Таблица истории операций -->
<div class=\"table-outter\">

  <div class=\"operation-history-table table\">
    <div id=\"product-list\">
      {% partial 'operation-history/history-item' histories=histories %}
    </div>
  </div>
</div>", "C:\\OSPanel\\domains\\inventpro-test\\themes\\invent-pro\\pages\\operation-history.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["partial" => 1];
        static $filters = [];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['partial'],
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
