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

/* C:\OSPanel\domains\inventpro\themes\invent-pro\pages\operation-history.htm */
class __TwigTemplate_c700ade2deab8c484efa0939da84a8a5 extends Template
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
        yield "<div class=\"operation-history__box box--light\">

  <div class=\"operation-history__count\">Всего таких операций: <span>";
        // line 3
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(($context["filteredCount"] ?? null), 3, $this->source), "html", null, true);
        yield " </span></div>

  <!-- Форма фильтрации -->
  <form method=\"get\" class=\"operation-history__filters\">

    <!-- Тип операции -->
    <div class=\"custom-select operation-history__filters-select--type\" data-name=\"type\">
      <div class=\"selected operation-history__filters-selected\">
        ";
        // line 11
        yield (((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["request"] ?? null), "get", ["type"], "method", false, false, true, 11)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, ($context["types"] ?? null), "where", ["id", CoreExtension::getAttribute($this->env, $this->source, ($context["request"] ?? null), "get", ["type"], "method", false, false, true, 11)], "method", false, false, true, 11), "first", [], "any", false, false, true, 11), "name", [], "any", false, false, true, 11), 11, $this->source), "html", null, true)) : ("Тип операции"));
        yield "
      </div>

      <div class=\"options operation-history__filters-options dropdown\">
        <div class=\"option operation-history__filters-option--type\" data-value=\"\">Все типы</div>
        ";
        // line 16
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["types"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["type"]) {
            // line 17
            yield "          <div class=\"option operation-history__filters-option--type\" data-value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["type"], "id", [], "any", false, false, true, 17), 17, $this->source), "html", null, true);
            yield "\">
            ";
            // line 18
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["type"], "name", [], "any", false, false, true, 18), 18, $this->source), "html", null, true);
            yield "
          </div>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['type'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 21
        yield "      </div>
    </div>

    <!-- Контрагент -->
    <div class=\"custom-select operation-history__filters-select--counteragent\" data-name=\"counteragent\">
      <div class=\"selected operation-history__filters-selected\">
          ";
        // line 27
        yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["request"] ?? null), "get", ["counteragent"], "method", false, false, true, 27)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["request"] ?? null), "get", ["counteragent"], "method", false, false, true, 27), 27, $this->source), "html", null, true)) : ("Контрагент"));
        yield "
      </div>
      <div class=\"options operation-history__filters-options dropdown\">
        <div class=\"option operation-history__filters-option\" data-value=\"\">Все контрагенты</div>
        ";
        // line 31
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["counteragents"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["ca"]) {
            // line 32
            yield "          <div class=\"option operation-history__filters-option--counteragent\" data-value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed($context["ca"], 32, $this->source), "html", null, true);
            yield "\">
            ";
            // line 33
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed($context["ca"], 33, $this->source), "html", null, true);
            yield "
          </div>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['ca'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 36
        yield "      </div>
    </div>

    <!-- Год -->
  <div class=\"custom-select operation-history__filters-select--year\" data-name=\"year\">
    <div class=\"selected operation-history__filters-selected\">
        ";
        // line 42
        yield ((CoreExtension::getAttribute($this->env, $this->source, ($context["request"] ?? null), "get", ["year"], "method", false, false, true, 42)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["request"] ?? null), "get", ["year"], "method", false, false, true, 42), 42, $this->source), "html", null, true)) : ("Год"));
        yield "
    </div>

    <div class=\"options operation-history__filters-options dropdown\">
      <div class=\"option\" data-value=\"\">Все годы</div>
      ";
        // line 47
        $context['_parent'] = $context;
        $context['_seq'] = CoreExtension::ensureTraversable(($context["years"] ?? null));
        foreach ($context['_seq'] as $context["_key"] => $context["year"]) {
            // line 48
            yield "        <div class=\"option operation-history__filters-option--year\" data-value=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed($context["year"], 48, $this->source), "html", null, true);
            yield "\">
          ";
            // line 49
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed($context["year"], 49, $this->source), "html", null, true);
            yield "
        </div>
      ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_key'], $context['year'], $context['_parent']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 52
        yield "    </div>
  </div>

    <input type=\"hidden\" name=\"type\" id=\"typeInput\">
    <input type=\"hidden\" name=\"counteragent\" id=\"counteragentInput\">
    <input type=\"hidden\" name=\"year\" id=\"yearInput\">

    <button type=\"submit\" class=\"button--md button--brand\">Применить</button>

  </form>

</div>

<!-- Таблица истории операций -->
<div class=\"operation-history-table table\">

  <div class=\"operation-history__item table-title\">
    <div class=\"operation-history__type\">Тип</div>
    <div class=\"operation-history__left\">
      <div class=\"operation-history__name\">Наименование</div>
      <div class=\"operation-history__number\">Инвентарный номер</div>
    </div>
    <div class=\"operation-history__price\">Цена</div>
    <div class=\"operation-history__quantity\">Количество</div>
    <div class=\"operation-history__date\">Дата</div>
    <div class=\"operation-history__counteragent\">Контрагент</div>
  </div>

  <div id=\"product-list\">
    ";
        // line 81
        $context['__cms_partial_params'] = [];
        $context['__cms_partial_params']['histories'] = ($context["histories"] ?? null)        ;
        echo $this->env->getExtension('Cms\Twig\Extension')->partialFunction("operation-history/history-item"        , $context['__cms_partial_params']        , true        );
        unset($context['__cms_partial_params']);
        // line 82
        yield "  </div>

</div>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\inventpro\\themes\\invent-pro\\pages\\operation-history.htm";
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
        return array (  188 => 82,  183 => 81,  152 => 52,  143 => 49,  138 => 48,  134 => 47,  126 => 42,  118 => 36,  109 => 33,  104 => 32,  100 => 31,  93 => 27,  85 => 21,  76 => 18,  71 => 17,  67 => 16,  59 => 11,  48 => 3,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<div class=\"operation-history__box box--light\">

  <div class=\"operation-history__count\">Всего таких операций: <span>{{ filteredCount }} </span></div>

  <!-- Форма фильтрации -->
  <form method=\"get\" class=\"operation-history__filters\">

    <!-- Тип операции -->
    <div class=\"custom-select operation-history__filters-select--type\" data-name=\"type\">
      <div class=\"selected operation-history__filters-selected\">
        {{ request.get('type') ? types.where('id', request.get('type')).first.name : 'Тип операции' }}
      </div>

      <div class=\"options operation-history__filters-options dropdown\">
        <div class=\"option operation-history__filters-option--type\" data-value=\"\">Все типы</div>
        {% for type in types %}
          <div class=\"option operation-history__filters-option--type\" data-value=\"{{ type.id }}\">
            {{ type.name }}
          </div>
        {% endfor %}
      </div>
    </div>

    <!-- Контрагент -->
    <div class=\"custom-select operation-history__filters-select--counteragent\" data-name=\"counteragent\">
      <div class=\"selected operation-history__filters-selected\">
          {{ request.get('counteragent') ?: 'Контрагент' }}
      </div>
      <div class=\"options operation-history__filters-options dropdown\">
        <div class=\"option operation-history__filters-option\" data-value=\"\">Все контрагенты</div>
        {% for ca in counteragents %}
          <div class=\"option operation-history__filters-option--counteragent\" data-value=\"{{ ca }}\">
            {{ ca }}
          </div>
        {% endfor %}
      </div>
    </div>

    <!-- Год -->
  <div class=\"custom-select operation-history__filters-select--year\" data-name=\"year\">
    <div class=\"selected operation-history__filters-selected\">
        {{ request.get('year') ?: 'Год' }}
    </div>

    <div class=\"options operation-history__filters-options dropdown\">
      <div class=\"option\" data-value=\"\">Все годы</div>
      {% for year in years %}
        <div class=\"option operation-history__filters-option--year\" data-value=\"{{ year }}\">
          {{ year }}
        </div>
      {% endfor %}
    </div>
  </div>

    <input type=\"hidden\" name=\"type\" id=\"typeInput\">
    <input type=\"hidden\" name=\"counteragent\" id=\"counteragentInput\">
    <input type=\"hidden\" name=\"year\" id=\"yearInput\">

    <button type=\"submit\" class=\"button--md button--brand\">Применить</button>

  </form>

</div>

<!-- Таблица истории операций -->
<div class=\"operation-history-table table\">

  <div class=\"operation-history__item table-title\">
    <div class=\"operation-history__type\">Тип</div>
    <div class=\"operation-history__left\">
      <div class=\"operation-history__name\">Наименование</div>
      <div class=\"operation-history__number\">Инвентарный номер</div>
    </div>
    <div class=\"operation-history__price\">Цена</div>
    <div class=\"operation-history__quantity\">Количество</div>
    <div class=\"operation-history__date\">Дата</div>
    <div class=\"operation-history__counteragent\">Контрагент</div>
  </div>

  <div id=\"product-list\">
    {% partial 'operation-history/history-item' histories=histories %}
  </div>

</div>", "C:\\OSPanel\\domains\\inventpro\\themes\\invent-pro\\pages\\operation-history.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["for" => 16, "partial" => 81];
        static $filters = ["escape" => 3];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['for', 'partial'],
                ['escape'],
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
