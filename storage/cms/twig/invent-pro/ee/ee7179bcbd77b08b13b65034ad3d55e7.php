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

/* C:\OSPanel\domains\inventpro-test\themes\invent-pro\partials\modals\modal_import_result.htm */
class __TwigTemplate_d60a50e3b6475486b1e55f2b6d3a8870 extends Template
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
        if ((($tmp = Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["differences"] ?? null))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 2
            yield "<div class=\"import table\">
    <div class=\"import__item table-title\">
        <div class=\"import__select\">
            <label for=\"\" class=\"checkbox\"><input type=\"checkbox\" id=\"select-all-diffs\" /></label>
        </div>
        <div class=\"import__name\">Найменування</div>
        <div class=\"import__quantity\">К-сть на складі</div>
        <div class=\"import__quantity-excel\">К-сть в Excel</div>
        <div class=\"import__price\">Ціна на складі</div>
        <div class=\"import__price-excel\">Ціна в Excel</div>
        <div class=\"import__sum\">Сума на складі</div>
        <div class=\"import__sum-excel\">Сума в Excel</div>
    </div>

    ";
            // line 16
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["differences"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["diff"]) {
                // line 17
                yield "        <div class=\"import__item table-item\">
            <div class=\"import__select\">
                <label for=\"\" class=\"checkbox\">
                    <input type=\"checkbox\"
                        class=\"diff-checkbox\"
                        checked
                        value=\"";
                // line 23
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "inv_number", [], "any", false, false, true, 23), 23, $this->source), "html", null, true);
                yield "\"
                        data-id=\"";
                // line 24
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "id", [], "any", false, false, true, 24), 24, $this->source), "html", null, true);
                yield "\"
                        data-quantity=\"";
                // line 25
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_quantity", [], "any", false, false, true, 25), 25, $this->source), "html", null, true);
                yield "\"
                        data-price=\"";
                // line 26
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_price", [], "any", false, false, true, 26), 26, $this->source), "html", null, true);
                yield "\"
                        data-sum=\"";
                // line 27
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_sum", [], "any", false, false, true, 27), 27, $this->source), "html", null, true);
                yield "\" />
                </label>

                <!-- Скрытые поля для передачи в updates -->
                <input type=\"hidden\" name=\"updates[";
                // line 31
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "id", [], "any", false, false, true, 31), 31, $this->source), "html", null, true);
                yield "][inv_number]\" value=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "inv_number", [], "any", false, false, true, 31), 31, $this->source), "html", null, true);
                yield "\">
                <input type=\"hidden\" name=\"updates[";
                // line 32
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "id", [], "any", false, false, true, 32), 32, $this->source), "html", null, true);
                yield "][quantity]\" value=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_quantity", [], "any", false, false, true, 32), 32, $this->source), "html", null, true);
                yield "\">
                <input type=\"hidden\" name=\"updates[";
                // line 33
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "id", [], "any", false, false, true, 33), 33, $this->source), "html", null, true);
                yield "][price]\" value=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_price", [], "any", false, false, true, 33), 33, $this->source), "html", null, true);
                yield "\">
                <input type=\"hidden\" name=\"updates[";
                // line 34
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "id", [], "any", false, false, true, 34), 34, $this->source), "html", null, true);
                yield "][sum]\" value=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_sum", [], "any", false, false, true, 34), 34, $this->source), "html", null, true);
                yield "\">
            </div>
            <div class=\"import__name\">";
                // line 36
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "name", [], "any", false, false, true, 36), 36, $this->source), "html", null, true);
                yield "</div>
            <div class=\"import__quantity ";
                // line 37
                yield (((CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "current_quantity", [], "any", false, false, true, 37) != CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_quantity", [], "any", false, false, true, 37))) ? ("import__cell--diff") : (""));
                yield "\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "current_quantity", [], "any", false, false, true, 37), 37, $this->source), "html", null, true);
                yield "</div>
            <div class=\"import__quantity-excel ";
                // line 38
                yield (((CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "current_quantity", [], "any", false, false, true, 38) != CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_quantity", [], "any", false, false, true, 38))) ? ("import__cell--diff") : (""));
                yield "\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_quantity", [], "any", false, false, true, 38), 38, $this->source), "html", null, true);
                yield "</div>
            <div class=\"import__price ";
                // line 39
                yield (((CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "price", [], "any", false, false, true, 39) != CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_price", [], "any", false, false, true, 39))) ? ("import__cell--diff") : (""));
                yield "\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "price", [], "any", false, false, true, 39), 39, $this->source), "html", null, true);
                yield "</div>
            <div class=\"import__price-excel ";
                // line 40
                yield (((CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "price", [], "any", false, false, true, 40) != CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_price", [], "any", false, false, true, 40))) ? ("import__cell--diff") : (""));
                yield "\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_price", [], "any", false, false, true, 40), 40, $this->source), "html", null, true);
                yield "</div>
            <div class=\"import__sum ";
                // line 41
                yield (((CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "sum", [], "any", false, false, true, 41) != CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_sum", [], "any", false, false, true, 41))) ? ("import__cell--diff") : (""));
                yield "\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "sum", [], "any", false, false, true, 41), 41, $this->source), "html", null, true);
                yield "</div>
            <div class=\"import__sum-excel ";
                // line 42
                yield (((CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "sum", [], "any", false, false, true, 42) != CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_sum", [], "any", false, false, true, 42))) ? ("import__cell--diff") : (""));
                yield "\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_sum", [], "any", false, false, true, 42), 42, $this->source), "html", null, true);
                yield "</div>
        </div>
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['diff'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 45
            yield "</div>

<div class=\"import__bottom\">

    <button type=\"button\" class=\"button button--nm button--secondary\" id=\"download-differences\">
        Завантажити файл с відмінностями
    </button>

    <div class=\"import__bottom-right\">
        <button type=\"button\" class=\"button button--nm button--secondary\" id=\"cancel-differences\">
            Відмінити
        </button>
        <button type=\"button\" class=\"button button--nm button--brand\" id=\"apply-differences\">
            Зберегти зміни
        </button>
    </div>";
            // line 61
            yield "</div>";
            // line 62
            yield "
";
        } else {
            // line 64
            yield "<div class=\"alert alert--success\">
    Различий нет. Новых продуктов: ";
            // line 65
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(($context["newCount"] ?? null), 65, $this->source), "html", null, true);
            yield "
</div>
";
        }
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\inventpro-test\\themes\\invent-pro\\partials\\modals\\modal_import_result.htm";
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
        return array (  193 => 65,  190 => 64,  186 => 62,  184 => 61,  167 => 45,  156 => 42,  150 => 41,  144 => 40,  138 => 39,  132 => 38,  126 => 37,  122 => 36,  115 => 34,  109 => 33,  103 => 32,  97 => 31,  90 => 27,  86 => 26,  82 => 25,  78 => 24,  74 => 23,  66 => 17,  62 => 16,  46 => 2,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% if differences|length %}
<div class=\"import table\">
    <div class=\"import__item table-title\">
        <div class=\"import__select\">
            <label for=\"\" class=\"checkbox\"><input type=\"checkbox\" id=\"select-all-diffs\" /></label>
        </div>
        <div class=\"import__name\">Найменування</div>
        <div class=\"import__quantity\">К-сть на складі</div>
        <div class=\"import__quantity-excel\">К-сть в Excel</div>
        <div class=\"import__price\">Ціна на складі</div>
        <div class=\"import__price-excel\">Ціна в Excel</div>
        <div class=\"import__sum\">Сума на складі</div>
        <div class=\"import__sum-excel\">Сума в Excel</div>
    </div>

    {% for diff in differences %}
        <div class=\"import__item table-item\">
            <div class=\"import__select\">
                <label for=\"\" class=\"checkbox\">
                    <input type=\"checkbox\"
                        class=\"diff-checkbox\"
                        checked
                        value=\"{{ diff.inv_number }}\"
                        data-id=\"{{ diff.id }}\"
                        data-quantity=\"{{ diff.excel_quantity }}\"
                        data-price=\"{{ diff.excel_price }}\"
                        data-sum=\"{{ diff.excel_sum }}\" />
                </label>

                <!-- Скрытые поля для передачи в updates -->
                <input type=\"hidden\" name=\"updates[{{ diff.id }}][inv_number]\" value=\"{{ diff.inv_number }}\">
                <input type=\"hidden\" name=\"updates[{{ diff.id }}][quantity]\" value=\"{{ diff.excel_quantity }}\">
                <input type=\"hidden\" name=\"updates[{{ diff.id }}][price]\" value=\"{{ diff.excel_price }}\">
                <input type=\"hidden\" name=\"updates[{{ diff.id }}][sum]\" value=\"{{ diff.excel_sum }}\">
            </div>
            <div class=\"import__name\">{{ diff.name }}</div>
            <div class=\"import__quantity {{ diff.current_quantity != diff.excel_quantity ? 'import__cell--diff' : '' }}\">{{ diff.current_quantity }}</div>
            <div class=\"import__quantity-excel {{ diff.current_quantity != diff.excel_quantity ? 'import__cell--diff' : '' }}\">{{ diff.excel_quantity }}</div>
            <div class=\"import__price {{ diff.price != diff.excel_price ? 'import__cell--diff' : '' }}\">{{ diff.price }}</div>
            <div class=\"import__price-excel {{ diff.price != diff.excel_price ? 'import__cell--diff' : '' }}\">{{ diff.excel_price }}</div>
            <div class=\"import__sum {{ diff.sum != diff.excel_sum ? 'import__cell--diff' : '' }}\">{{ diff.sum }}</div>
            <div class=\"import__sum-excel {{ diff.sum != diff.excel_sum ? 'import__cell--diff' : '' }}\">{{ diff.excel_sum }}</div>
        </div>
    {% endfor %}
</div>

<div class=\"import__bottom\">

    <button type=\"button\" class=\"button button--nm button--secondary\" id=\"download-differences\">
        Завантажити файл с відмінностями
    </button>

    <div class=\"import__bottom-right\">
        <button type=\"button\" class=\"button button--nm button--secondary\" id=\"cancel-differences\">
            Відмінити
        </button>
        <button type=\"button\" class=\"button button--nm button--brand\" id=\"apply-differences\">
            Зберегти зміни
        </button>
    </div>{# import__bottom-right #}
</div>{# import__bottom #}

{% else %}
<div class=\"alert alert--success\">
    Различий нет. Новых продуктов: {{ newCount }}
</div>
{% endif %}", "C:\\OSPanel\\domains\\inventpro-test\\themes\\invent-pro\\partials\\modals\\modal_import_result.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["if" => 1, "for" => 16];
        static $filters = ["length" => 1, "escape" => 23];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['if', 'for'],
                ['length', 'escape'],
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
