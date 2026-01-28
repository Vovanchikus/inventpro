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

/* C:\OSPanel\domains\inventpro-test\themes\invent-pro\pages\warehouse.htm */
class __TwigTemplate_694ed02bdc740c8cb6075e165fe5ffb3 extends Template
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
        yield "<div class=\"warehouse-table table\">

  <div class=\"warehouse__item table-title\">
  <div class=\"warehouse__checkbox\">
    <label for=\"\" class=\"checkbox\">
      <input type=\"checkbox\" name=\"\" id=\"\">
    </label>
  </div>
    <div class=\"warehouse__left\">
      <div class=\"warehouse__name\">Наименование</div>
      <div class=\"warehouse__number\">Номенклатурный №</div>
    </div>
    <div class=\"warehouse__unit\">Ед.измерения</div>
    <div class=\"warehouse__price\">Цена</div>
    <div class=\"warehouse__quantity\">Количество</div>
    <div class=\"warehouse__sum\">Сумма</div>
  </div>

  ";
        // line 19
        $context['__cms_partial_params'] = [];
        echo $this->env->getExtension('Cms\Twig\Extension')->partialFunction("warehouse/warehouse-product"        , $context['__cms_partial_params']        , true        );
        unset($context['__cms_partial_params']);
        // line 20
        yield "
</div>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\inventpro-test\\themes\\invent-pro\\pages\\warehouse.htm";
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
        return array (  68 => 20,  64 => 19,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<div class=\"warehouse-table table\">

  <div class=\"warehouse__item table-title\">
  <div class=\"warehouse__checkbox\">
    <label for=\"\" class=\"checkbox\">
      <input type=\"checkbox\" name=\"\" id=\"\">
    </label>
  </div>
    <div class=\"warehouse__left\">
      <div class=\"warehouse__name\">Наименование</div>
      <div class=\"warehouse__number\">Номенклатурный №</div>
    </div>
    <div class=\"warehouse__unit\">Ед.измерения</div>
    <div class=\"warehouse__price\">Цена</div>
    <div class=\"warehouse__quantity\">Количество</div>
    <div class=\"warehouse__sum\">Сумма</div>
  </div>

  {% partial 'warehouse/warehouse-product' %}

</div>", "C:\\OSPanel\\domains\\inventpro-test\\themes\\invent-pro\\pages\\warehouse.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["partial" => 19];
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
