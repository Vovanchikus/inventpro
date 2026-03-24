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
        yield "<input type=\"hidden\" id=\"import-download-report\" value=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((array_key_exists("downloadReportEncoded", $context)) ? (Twig\Extension\CoreExtension::default($this->sandbox->ensureToStringAllowed(($context["downloadReportEncoded"] ?? null), 1, $this->source), "")) : ("")), "html", null, true);
        yield "\">

";
        // line 3
        $context["hasDiff"] = Twig\Extension\CoreExtension::length($this->env->getCharset(), $this->sandbox->ensureToStringAllowed(($context["differences"] ?? null), 3, $this->source));
        // line 4
        $context["hasNew"] = Twig\Extension\CoreExtension::length($this->env->getCharset(), ((array_key_exists("newProducts", $context)) ? (Twig\Extension\CoreExtension::default($this->sandbox->ensureToStringAllowed(($context["newProducts"] ?? null), 4, $this->source), [])) : ([])));
        // line 5
        $context["hasMissing"] = Twig\Extension\CoreExtension::length($this->env->getCharset(), ((array_key_exists("missingProducts", $context)) ? (Twig\Extension\CoreExtension::default($this->sandbox->ensureToStringAllowed(($context["missingProducts"] ?? null), 5, $this->source), [])) : ([])));
        // line 6
        $context["hasAmbiguous"] = Twig\Extension\CoreExtension::length($this->env->getCharset(), ((array_key_exists("ambiguousMatches", $context)) ? (Twig\Extension\CoreExtension::default($this->sandbox->ensureToStringAllowed(($context["ambiguousMatches"] ?? null), 6, $this->source), [])) : ([])));
        // line 7
        $context["hasSplit"] = Twig\Extension\CoreExtension::length($this->env->getCharset(), ((array_key_exists("splitCandidates", $context)) ? (Twig\Extension\CoreExtension::default($this->sandbox->ensureToStringAllowed(($context["splitCandidates"] ?? null), 7, $this->source), [])) : ([])));
        // line 8
        $context["tabsCount"] = (((((((($tmp = ($context["hasDiff"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? (1) : (0)) + (((($tmp = ($context["hasNew"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? (1) : (0))) + (((($tmp = ($context["hasMissing"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? (1) : (0))) + (((($tmp = ($context["hasAmbiguous"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? (1) : (0))) + (((($tmp = ($context["hasSplit"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? (1) : (0)));
        // line 9
        yield "
";
        // line 10
        if ((($context["tabsCount"] ?? null) > 0)) {
            // line 11
            yield "<div id=\"import-tabs\" class=\"notes-filter\" style=\"margin: 0 0 14px;\">
    ";
            // line 12
            if ((($tmp = ($context["hasDiff"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 13
                yield "        <button type=\"button\" class=\"notes-filter__tab import-tab-btn is-active\"
                data-tab-target=\"differences\"
                data-tab-title=\"Відмінності зі складом\"
                data-tab-subtitle=\"Позиції з різницею у кількості, ціні, сумі або інвентарному номері.\">
            <span class=\"notes-filter__label\">Відмінності</span>
            <span class=\"notes-filter__count label--extra-sm label--secondary\" data-count>";
                // line 18
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::length($this->env->getCharset(), $this->sandbox->ensureToStringAllowed(($context["differences"] ?? null), 18, $this->source)), "html", null, true);
                yield "</span>
        </button>
    ";
            }
            // line 21
            yield "    ";
            if ((($tmp = ($context["hasNew"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 22
                yield "        <button type=\"button\" class=\"notes-filter__tab import-tab-btn ";
                yield (((($tmp =  !($context["hasDiff"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("is-active") : (""));
                yield "\"
                data-tab-target=\"new\"
                data-tab-title=\"Нові позиції\"
                data-tab-subtitle=\"Товари, яких не було в БД та які будуть додані після підтвердження.\">
            <span class=\"notes-filter__label\">Нові</span>
            <span class=\"notes-filter__count label--extra-sm label--secondary\" data-count>";
                // line 27
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::length($this->env->getCharset(), $this->sandbox->ensureToStringAllowed(($context["newProducts"] ?? null), 27, $this->source)), "html", null, true);
                yield "</span>
        </button>
    ";
            }
            // line 30
            yield "    ";
            if ((($tmp = ($context["hasMissing"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 31
                yield "        <button type=\"button\" class=\"notes-filter__tab import-tab-btn ";
                yield ((( !($context["hasDiff"] ?? null) &&  !($context["hasNew"] ?? null))) ? ("is-active") : (""));
                yield "\"
                data-tab-target=\"missing\"
                data-tab-title=\"Позиції, що зникли\"
                data-tab-subtitle=\"Товари, що є в БД, але відсутні у поточному Excel, включно з нульовими залишками.\">
            <span class=\"notes-filter__label\">Відсутні в Excel</span>
            <span class=\"notes-filter__count label--extra-sm label--secondary\" data-count>";
                // line 36
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::length($this->env->getCharset(), $this->sandbox->ensureToStringAllowed(($context["missingProducts"] ?? null), 36, $this->source)), "html", null, true);
                yield "</span>
        </button>
    ";
            }
            // line 39
            yield "    ";
            if ((($tmp = ($context["hasAmbiguous"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 40
                yield "        <button type=\"button\" class=\"notes-filter__tab import-tab-btn ";
                yield (((( !($context["hasDiff"] ?? null) &&  !($context["hasNew"] ?? null)) &&  !($context["hasMissing"] ?? null))) ? ("is-active") : (""));
                yield "\"
                data-tab-target=\"ambiguous\"
                data-tab-title=\"Ручна перевірка\"
                data-tab-subtitle=\"Оберіть вручну правильний товар у БД для неоднозначних інвентарних номерів.\">
            <span class=\"notes-filter__label\">Ручна перевірка</span>
            <span class=\"notes-filter__count label--extra-sm label--secondary\" data-count>";
                // line 45
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::length($this->env->getCharset(), $this->sandbox->ensureToStringAllowed(($context["ambiguousMatches"] ?? null), 45, $this->source)), "html", null, true);
                yield "</span>
        </button>
    ";
            }
            // line 48
            yield "    ";
            if ((($tmp = ($context["hasSplit"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                // line 49
                yield "        <button type=\"button\" class=\"notes-filter__tab import-tab-btn ";
                yield ((((( !($context["hasDiff"] ?? null) &&  !($context["hasNew"] ?? null)) &&  !($context["hasMissing"] ?? null)) &&  !($context["hasAmbiguous"] ?? null))) ? ("is-active") : (""));
                yield "\"
                data-tab-target=\"split\"
                data-tab-title=\"Розподіл операцій\"
                data-tab-subtitle=\"Оберіть для кожної нової позиції операцію приходу, яка має бути до неї прив'язана.\">
            <span class=\"notes-filter__label\">Розподіл операцій</span>
            <span class=\"notes-filter__count label--extra-sm label--secondary\" data-count>";
                // line 54
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(Twig\Extension\CoreExtension::length($this->env->getCharset(), $this->sandbox->ensureToStringAllowed(($context["splitCandidates"] ?? null), 54, $this->source)), "html", null, true);
                yield "</span>
        </button>
    ";
            }
            // line 57
            yield "</div>
";
        }
        // line 59
        yield "
";
        // line 60
        if ((($tmp = Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["differences"] ?? null))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 61
            yield "<div class=\"import-modal-section\" data-tab-key=\"differences\" data-modal-title=\"Відмінності зі складом\" data-modal-subtitle=\"Позиції з різницею у кількості, ціні, сумі або інвентарному номері.\">
<div class=\"table\" style=\"margin-bottom: 16px; border-radius: var(--radius-md);\">
    <div class=\"warehouse__item table-title\" style=\"background: var(--bg-box); border-radius: 0; width: 100%;\">
        <div class=\"warehouse__checkbox\">
            <label for=\"\" class=\"checkbox\"><input type=\"checkbox\" id=\"select-all-diffs\" /></label>
        </div>
        <div class=\"warehouse__left\">
            <div class=\"warehouse__name\">Найменування</div>
            <div class=\"warehouse__number\">Номенклатурний №</div>
        </div>
        <div class=\"warehouse__unit\">К-сть (Склад / Excel)</div>
        <div class=\"warehouse__price\">Ціна (Склад / Excel)</div>
        <div class=\"warehouse__quantity\">Сума (Склад / Excel)</div>
        <div class=\"warehouse__sum\">Статус</div>
    </div>

    ";
            // line 77
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["differences"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["diff"]) {
                // line 78
                yield "        <div class=\"warehouse__item table-item diff-row\"
             data-name=\"";
                // line 79
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "name", [], "any", false, false, true, 79), 79, $this->source), "html", null, true);
                yield "\"
             data-current-quantity=\"";
                // line 80
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "current_quantity", [], "any", false, false, true, 80), 80, $this->source), "html", null, true);
                yield "\"
             data-excel-quantity=\"";
                // line 81
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_quantity", [], "any", false, false, true, 81), 81, $this->source), "html", null, true);
                yield "\"
             data-current-price=\"";
                // line 82
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "price", [], "any", false, false, true, 82), 82, $this->source), "html", null, true);
                yield "\"
             data-excel-price=\"";
                // line 83
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_price", [], "any", false, false, true, 83), 83, $this->source), "html", null, true);
                yield "\"
             data-current-sum=\"";
                // line 84
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "sum", [], "any", false, false, true, 84), 84, $this->source), "html", null, true);
                yield "\"
             data-excel-sum=\"";
                // line 85
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_sum", [], "any", false, false, true, 85), 85, $this->source), "html", null, true);
                yield "\">
            <div class=\"warehouse__checkbox\">
                <label for=\"\" class=\"checkbox\">
                    <input type=\"checkbox\"
                        class=\"diff-checkbox\"
                        checked
                        value=\"";
                // line 91
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "inv_number", [], "any", false, false, true, 91), 91, $this->source), "html", null, true);
                yield "\"
                        data-id=\"";
                // line 92
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "id", [], "any", false, false, true, 92), 92, $this->source), "html", null, true);
                yield "\"
                        data-quantity=\"";
                // line 93
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_quantity", [], "any", false, false, true, 93), 93, $this->source), "html", null, true);
                yield "\"
                        data-price=\"";
                // line 94
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_price", [], "any", false, false, true, 94), 94, $this->source), "html", null, true);
                yield "\"
                        data-sum=\"";
                // line 95
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_sum", [], "any", false, false, true, 95), 95, $this->source), "html", null, true);
                yield "\" />
                </label>

                <!-- Скрытые поля для передачи в updates -->
                <input type=\"hidden\" name=\"updates[";
                // line 99
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "id", [], "any", false, false, true, 99), 99, $this->source), "html", null, true);
                yield "][inv_number]\" value=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "inv_number", [], "any", false, false, true, 99), 99, $this->source), "html", null, true);
                yield "\">
                <input type=\"hidden\" name=\"updates[";
                // line 100
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "id", [], "any", false, false, true, 100), 100, $this->source), "html", null, true);
                yield "][excel_inv_number]\" value=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_inv_number", [], "any", true, true, true, 100)) ? (Twig\Extension\CoreExtension::default($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_inv_number", [], "any", false, false, true, 100), 100, $this->source), $this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "inv_number", [], "any", false, false, true, 100), 100, $this->source))) : (CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "inv_number", [], "any", false, false, true, 100))), "html", null, true);
                yield "\">
                <input type=\"hidden\" name=\"updates[";
                // line 101
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "id", [], "any", false, false, true, 101), 101, $this->source), "html", null, true);
                yield "][quantity]\" value=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_quantity", [], "any", false, false, true, 101), 101, $this->source), "html", null, true);
                yield "\">
                <input type=\"hidden\" name=\"updates[";
                // line 102
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "id", [], "any", false, false, true, 102), 102, $this->source), "html", null, true);
                yield "][price]\" value=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_price", [], "any", false, false, true, 102), 102, $this->source), "html", null, true);
                yield "\">
                <input type=\"hidden\" name=\"updates[";
                // line 103
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "id", [], "any", false, false, true, 103), 103, $this->source), "html", null, true);
                yield "][sum]\" value=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_sum", [], "any", false, false, true, 103), 103, $this->source), "html", null, true);
                yield "\">
            </div>
            <div class=\"warehouse__left ";
                // line 105
                yield (((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "inv_changed", [], "any", false, false, true, 105)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("import__cell--diff") : (""));
                yield "\">
                <div class=\"warehouse__name\">";
                // line 106
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "name", [], "any", false, false, true, 106), 106, $this->source), "html", null, true);
                yield "</div>
                <div class=\"warehouse__number\">";
                // line 107
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "inv_number", [], "any", false, false, true, 107), 107, $this->source), "html", null, true);
                yield "</div>
                ";
                // line 108
                if ((CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "inv_changed", [], "any", false, false, true, 108) &&  !Twig\Extension\CoreExtension::testEmpty(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_inv_number", [], "any", false, false, true, 108)))) {
                    // line 109
                    yield "                    <div style=\"font-size:12px; color: var(--text-secondary);\">
                        ";
                    // line 110
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "inv_number", [], "any", false, false, true, 110), 110, $this->source), "html", null, true);
                    yield " → ";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_inv_number", [], "any", false, false, true, 110), 110, $this->source), "html", null, true);
                    yield "
                    </div>
                ";
                }
                // line 113
                yield "            </div>
            <div class=\"warehouse__unit ";
                // line 114
                yield (((CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "current_quantity", [], "any", false, false, true, 114) != CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_quantity", [], "any", false, false, true, 114))) ? ("import__cell--diff") : (""));
                yield "\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "current_quantity", [], "any", false, false, true, 114), 114, $this->source), "html", null, true);
                yield " → ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_quantity", [], "any", false, false, true, 114), 114, $this->source), "html", null, true);
                yield "</div>
            <div class=\"warehouse__price ";
                // line 115
                yield (((CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "price", [], "any", false, false, true, 115) != CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_price", [], "any", false, false, true, 115))) ? ("import__cell--diff") : (""));
                yield "\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "price", [], "any", false, false, true, 115), 115, $this->source), "html", null, true);
                yield " → ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_price", [], "any", false, false, true, 115), 115, $this->source), "html", null, true);
                yield "</div>
            <div class=\"warehouse__quantity ";
                // line 116
                yield (((CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "sum", [], "any", false, false, true, 116) != CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_sum", [], "any", false, false, true, 116))) ? ("import__cell--diff") : (""));
                yield "\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "sum", [], "any", false, false, true, 116), 116, $this->source), "html", null, true);
                yield " → ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_sum", [], "any", false, false, true, 116), 116, $this->source), "html", null, true);
                yield "</div>
            <div class=\"warehouse__sum ";
                // line 117
                yield ((((((CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "current_quantity", [], "any", false, false, true, 117) != CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_quantity", [], "any", false, false, true, 117)) || (CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "price", [], "any", false, false, true, 117) != CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_price", [], "any", false, false, true, 117))) || (CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "sum", [], "any", false, false, true, 117) != CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "excel_sum", [], "any", false, false, true, 117))) || CoreExtension::getAttribute($this->env, $this->source, $context["diff"], "inv_changed", [], "any", false, false, true, 117))) ? ("import__cell--diff") : (""));
                yield "\">
                Є зміни
            </div>
        </div>
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['diff'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 122
            yield "</div>
</div>
";
        }
        // line 125
        yield "
";
        // line 126
        if ((($tmp = Twig\Extension\CoreExtension::length($this->env->getCharset(), ((array_key_exists("newProducts", $context)) ? (Twig\Extension\CoreExtension::default(($context["newProducts"] ?? null), [])) : ([])))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 127
            yield "<div class=\"import-modal-section\" data-tab-key=\"new\" data-modal-title=\"Нові позиції\" data-modal-subtitle=\"Товари, яких не було в БД та які будуть додані після підтвердження.\">
<div class=\"table\" style=\"margin-bottom: 16px; border-radius: var(--radius-md);\">
    <div class=\"warehouse__item table-title\" style=\"background: var(--bg-box); border-radius: 0; width: 100%;\">
        <div class=\"warehouse__checkbox\"></div>
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
            // line 141
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["newProducts"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                // line 142
                yield "        <div class=\"warehouse__item table-item\">
            <div class=\"warehouse__checkbox\"></div>
            <div class=\"warehouse__left\">
                <div class=\"warehouse__name\">";
                // line 145
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "name", [], "any", false, false, true, 145), 145, $this->source), "html", null, true);
                yield "</div>
                <div class=\"warehouse__number\">";
                // line 146
                yield ((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "inv_number", [], "any", false, false, true, 146)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "inv_number", [], "any", false, false, true, 146), 146, $this->source), "html", null, true)) : ("-"));
                yield "</div>
            </div>
            <div class=\"warehouse__unit\">";
                // line 148
                yield ((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "unit", [], "any", false, false, true, 148)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "unit", [], "any", false, false, true, 148), 148, $this->source), "html", null, true)) : ("-"));
                yield "</div>
            <div class=\"warehouse__price\">";
                // line 149
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "excel_price", [], "any", false, false, true, 149), 149, $this->source), "html", null, true);
                yield "</div>
            <div class=\"warehouse__quantity\">";
                // line 150
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "excel_quantity", [], "any", false, false, true, 150), 150, $this->source), "html", null, true);
                yield "</div>
            <div class=\"warehouse__sum\">";
                // line 151
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "excel_sum", [], "any", false, false, true, 151), 151, $this->source), "html", null, true);
                yield "</div>
        </div>
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['item'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 154
            yield "</div>
</div>
";
        }
        // line 157
        yield "
";
        // line 158
        if ((($tmp = Twig\Extension\CoreExtension::length($this->env->getCharset(), ((array_key_exists("missingProducts", $context)) ? (Twig\Extension\CoreExtension::default(($context["missingProducts"] ?? null), [])) : ([])))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 159
            yield "<div class=\"import-modal-section\" data-tab-key=\"missing\" data-modal-title=\"Позиції, що зникли\" data-modal-subtitle=\"Товари, що є в БД, але відсутні у поточному Excel, включно з нульовими залишками.\">
<div class=\"table\" style=\"margin-bottom: 16px; border-radius: var(--radius-md);\">
    <div class=\"warehouse__item table-title\" style=\"background: var(--bg-box); border-radius: 0; width: 100%;\">
        <div class=\"warehouse__checkbox\"></div>
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
            // line 173
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["missingProducts"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                // line 174
                yield "        <div class=\"warehouse__item table-item\">
            <div class=\"warehouse__checkbox\"></div>
            <div class=\"warehouse__left\">
                <div class=\"warehouse__name\">";
                // line 177
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "name", [], "any", false, false, true, 177), 177, $this->source), "html", null, true);
                yield "</div>
                <div class=\"warehouse__number\">";
                // line 178
                yield ((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "inv_number", [], "any", false, false, true, 178)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "inv_number", [], "any", false, false, true, 178), 178, $this->source), "html", null, true)) : ("-"));
                yield "</div>
            </div>
            <div class=\"warehouse__unit\">";
                // line 180
                yield ((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "unit", [], "any", false, false, true, 180)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "unit", [], "any", false, false, true, 180), 180, $this->source), "html", null, true)) : ("-"));
                yield "</div>
            <div class=\"warehouse__price\">";
                // line 181
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "current_price", [], "any", false, false, true, 181), 181, $this->source), "html", null, true);
                yield "</div>
            <div class=\"warehouse__quantity\">";
                // line 182
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "current_quantity", [], "any", false, false, true, 182), 182, $this->source), "html", null, true);
                yield "</div>
            <div class=\"warehouse__sum\">";
                // line 183
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "current_sum", [], "any", false, false, true, 183), 183, $this->source), "html", null, true);
                yield "</div>
        </div>
    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['item'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 186
            yield "</div>
</div>
";
        }
        // line 189
        yield "
";
        // line 190
        if ((($tmp = Twig\Extension\CoreExtension::length($this->env->getCharset(), ((array_key_exists("ambiguousMatches", $context)) ? (Twig\Extension\CoreExtension::default(($context["ambiguousMatches"] ?? null), [])) : ([])))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 191
            yield "<div class=\"import-modal-section\" data-tab-key=\"ambiguous\" data-modal-title=\"Ручна перевірка\" data-modal-subtitle=\"Оберіть вручну правильний товар у БД для неоднозначних інвентарних номерів.\">
<div class=\"import table\" style=\"margin-bottom: 16px;\">
    <div class=\"import__item table-title\">
        <div class=\"import__name\" style=\"grid-column: 1 / -1;\">Потрібна ручна перевірка (неоднозначне співпадіння) — оберіть відповідний товар у БД</div>
    </div>

    ";
            // line 197
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["ambiguousMatches"] ?? null));
            $context['loop'] = [
              'parent' => $context['_parent'],
              'index0' => 0,
              'index'  => 1,
              'first'  => true,
            ];
            if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
                $length = count($context['_seq']);
                $context['loop']['revindex0'] = $length - 1;
                $context['loop']['revindex'] = $length;
                $context['loop']['length'] = $length;
                $context['loop']['last'] = 1 === $length;
            }
            foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                // line 198
                yield "        <div class=\"import__item table-item ambiguous-item\"
             data-index=\"";
                // line 199
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "index0", [], "any", false, false, true, 199), 199, $this->source), "html", null, true);
                yield "\"
             data-excel-name=\"";
                // line 200
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "excel_name", [], "any", false, false, true, 200), 200, $this->source));
                yield "\"
             data-excel-inv=\"";
                // line 201
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "excel_inv_number", [], "any", false, false, true, 201), 201, $this->source));
                yield "\"
             data-excel-quantity=\"";
                // line 202
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "excel_quantity", [], "any", true, true, true, 202)) ? (Twig\Extension\CoreExtension::default($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "excel_quantity", [], "any", false, false, true, 202), 202, $this->source), 0)) : (0)), "html", null, true);
                yield "\"
             data-excel-price=\"";
                // line 203
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "excel_price", [], "any", true, true, true, 203)) ? (Twig\Extension\CoreExtension::default($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "excel_price", [], "any", false, false, true, 203), 203, $this->source), 0)) : (0)), "html", null, true);
                yield "\"
             data-excel-sum=\"";
                // line 204
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "excel_sum", [], "any", true, true, true, 204)) ? (Twig\Extension\CoreExtension::default($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "excel_sum", [], "any", false, false, true, 204), 204, $this->source), 0)) : (0)), "html", null, true);
                yield "\">
            <div class=\"import__name\">
                ";
                // line 206
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "excel_name", [], "any", false, false, true, 206), 206, $this->source), "html", null, true);
                yield " (";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "excel_inv_number", [], "any", false, false, true, 206), 206, $this->source), "html", null, true);
                yield ")
                <div style=\"font-size:12px; color: var(--text-secondary);\">
                    К-сть: ";
                // line 208
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "excel_quantity", [], "any", true, true, true, 208)) ? (Twig\Extension\CoreExtension::default($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "excel_quantity", [], "any", false, false, true, 208), 208, $this->source), 0)) : (0)), "html", null, true);
                yield ", Ціна: ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "excel_price", [], "any", true, true, true, 208)) ? (Twig\Extension\CoreExtension::default($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "excel_price", [], "any", false, false, true, 208), 208, $this->source), 0)) : (0)), "html", null, true);
                yield ", Сума: ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "excel_sum", [], "any", true, true, true, 208)) ? (Twig\Extension\CoreExtension::default($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "excel_sum", [], "any", false, false, true, 208), 208, $this->source), 0)) : (0)), "html", null, true);
                yield "
                </div>
            </div>
            <div class=\"import__quantity\" style=\"white-space: normal;\">
                ";
                // line 212
                if ((($tmp = Twig\Extension\CoreExtension::length($this->env->getCharset(), CoreExtension::getAttribute($this->env, $this->source, $context["item"], "candidates", [], "any", false, false, true, 212))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    // line 213
                    yield "                    ";
                    $context['_parent'] = $context;
                    $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "candidates", [], "any", false, false, true, 213));
                    $context['loop'] = [
                      'parent' => $context['_parent'],
                      'index0' => 0,
                      'index'  => 1,
                      'first'  => true,
                    ];
                    if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof \Countable)) {
                        $length = count($context['_seq']);
                        $context['loop']['revindex0'] = $length - 1;
                        $context['loop']['revindex'] = $length;
                        $context['loop']['length'] = $length;
                        $context['loop']['last'] = 1 === $length;
                    }
                    foreach ($context['_seq'] as $context["_key"] => $context["candidate"]) {
                        // line 214
                        yield "                        <label class=\"checkbox\" style=\"display:block; margin-bottom: 6px;\">
                            <input
                                type=\"radio\"
                                class=\"ambiguous-choice\"
                                name=\"ambiguous_choice_";
                        // line 218
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, CoreExtension::getAttribute($this->env, $this->source, $context["loop"], "parent", [], "any", false, false, true, 218), "index0", [], "any", false, false, true, 218), 218, $this->source), "html", null, true);
                        yield "\"
                                value=\"";
                        // line 219
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["candidate"], "id", [], "any", false, false, true, 219), 219, $this->source), "html", null, true);
                        yield "\"
                                data-product-id=\"";
                        // line 220
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["candidate"], "id", [], "any", false, false, true, 220), 220, $this->source), "html", null, true);
                        yield "\"
                                data-product-name=\"";
                        // line 221
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["candidate"], "name", [], "any", false, false, true, 221), 221, $this->source));
                        yield "\"
                                data-product-inv=\"";
                        // line 222
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["candidate"], "inv_number", [], "any", false, false, true, 222), 222, $this->source));
                        yield "\"
                            />
                            <span>";
                        // line 224
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["candidate"], "name", [], "any", false, false, true, 224), 224, $this->source), "html", null, true);
                        yield " (";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["candidate"], "inv_number", [], "any", false, false, true, 224), 224, $this->source), "html", null, true);
                        yield ")</span>
                        </label>
                    ";
                        ++$context['loop']['index0'];
                        ++$context['loop']['index'];
                        $context['loop']['first'] = false;
                        if (isset($context['loop']['revindex0'], $context['loop']['revindex'])) {
                            --$context['loop']['revindex0'];
                            --$context['loop']['revindex'];
                            $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                        }
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_key'], $context['candidate'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 227
                    yield "                ";
                } else {
                    // line 228
                    yield "                    <div>Немає кандидатів</div>
                ";
                }
                // line 230
                yield "            </div>
        </div>
    ";
                ++$context['loop']['index0'];
                ++$context['loop']['index'];
                $context['loop']['first'] = false;
                if (isset($context['loop']['revindex0'], $context['loop']['revindex'])) {
                    --$context['loop']['revindex0'];
                    --$context['loop']['revindex'];
                    $context['loop']['last'] = 0 === $context['loop']['revindex0'];
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 233
            yield "</div>
</div>
";
        }
        // line 236
        yield "
";
        // line 237
        if ((($tmp = Twig\Extension\CoreExtension::length($this->env->getCharset(), ((array_key_exists("splitCandidates", $context)) ? (Twig\Extension\CoreExtension::default(($context["splitCandidates"] ?? null), [])) : ([])))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 238
            yield "<div class=\"import-modal-section\" data-tab-key=\"split\" data-modal-title=\"Розподіл операцій\" data-modal-subtitle=\"Оберіть, яку операцію застосувати до кожної нової позиції після розділення.\">
<div class=\"table\" style=\"margin-bottom: 16px; border-radius: var(--radius-md);\">
    <div class=\"warehouse__item table-title\" style=\"background: var(--bg-box); border-radius: 0; width: 100%;\">
        <div class=\"warehouse__checkbox\"></div>
        <div class=\"warehouse__left\">
            <div class=\"warehouse__name\">Нова позиція</div>
            <div class=\"warehouse__number\">Базова позиція</div>
        </div>
        <div class=\"warehouse__unit\">К-сть</div>
        <div class=\"warehouse__price\">Ціна</div>
        <div class=\"warehouse__quantity\">Операція</div>
        <div class=\"warehouse__sum\">Документ</div>
    </div>

    ";
            // line 252
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["splitCandidates"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["candidate"]) {
                // line 253
                yield "        ";
                $context['_parent'] = $context;
                $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, $context["candidate"], "rows", [], "any", false, false, true, 253));
                foreach ($context['_seq'] as $context["_key"] => $context["row"]) {
                    // line 254
                    yield "            <div class=\"warehouse__item table-item split-item\"
                 data-base-product-id=\"";
                    // line 255
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["candidate"], "base_product_id", [], "any", false, false, true, 255), 255, $this->source), "html", null, true);
                    yield "\"
                 data-excel-inv=\"";
                    // line 256
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "excel_inv_number", [], "any", false, false, true, 256), 256, $this->source));
                    yield "\">
                <div class=\"warehouse__checkbox\"></div>
                <div class=\"warehouse__left\">
                    <div class=\"warehouse__name\">";
                    // line 259
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["candidate"], "base_name", [], "any", false, false, true, 259), 259, $this->source), "html", null, true);
                    yield "</div>
                    <div class=\"warehouse__number\">";
                    // line 260
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["candidate"], "base_inv_number", [], "any", false, false, true, 260), 260, $this->source), "html", null, true);
                    yield " → ";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "excel_inv_number", [], "any", false, false, true, 260), 260, $this->source), "html", null, true);
                    yield "</div>
                </div>
                <div class=\"warehouse__unit\">";
                    // line 262
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "excel_quantity", [], "any", false, false, true, 262), 262, $this->source), "html", null, true);
                    yield "</div>
                <div class=\"warehouse__price\">";
                    // line 263
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "excel_price", [], "any", false, false, true, 263), 263, $this->source), "html", null, true);
                    yield "</div>
                <div class=\"warehouse__quantity\" style=\"white-space: normal; width: 100%;\">
                    <select class=\"split-operation-select\" style=\"width:100%; min-width: 240px;\"
                            data-base-product-id=\"";
                    // line 266
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["candidate"], "base_product_id", [], "any", false, false, true, 266), 266, $this->source), "html", null, true);
                    yield "\"
                            data-excel-inv=\"";
                    // line 267
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["row"], "excel_inv_number", [], "any", false, false, true, 267), 267, $this->source));
                    yield "\">
                        <option value=\"\">Оберіть операцію</option>
                        ";
                    // line 269
                    $context['_parent'] = $context;
                    $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, $context["candidate"], "operations", [], "any", false, false, true, 269));
                    foreach ($context['_seq'] as $context["_key"] => $context["op"]) {
                        // line 270
                        yield "                            <option value=\"";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "operation_id", [], "any", false, false, true, 270), 270, $this->source), "html", null, true);
                        yield "\"
                                data-op-qty=\"";
                        // line 271
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "quantity", [], "any", false, false, true, 271), 271, $this->source), "html", null, true);
                        yield "\"
                                data-op-sum=\"";
                        // line 272
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "sum", [], "any", false, false, true, 272), 272, $this->source), "html", null, true);
                        yield "\"
                                data-op-doc=\"";
                        // line 273
                        yield ((CoreExtension::getAttribute($this->env, $this->source, $context["op"], "doc_num", [], "any", false, false, true, 273)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "doc_num", [], "any", false, false, true, 273), 273, $this->source), "html", null, true)) : ("-"));
                        yield "\"
                                data-op-date=\"";
                        // line 274
                        yield ((CoreExtension::getAttribute($this->env, $this->source, $context["op"], "doc_date", [], "any", false, false, true, 274)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "doc_date", [], "any", false, false, true, 274), 274, $this->source), "html", null, true)) : ("-"));
                        yield "\">
                                #";
                        // line 275
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "operation_id", [], "any", false, false, true, 275), 275, $this->source), "html", null, true);
                        yield " • ";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "operation_type", [], "any", false, false, true, 275), 275, $this->source), "html", null, true);
                        yield " • ";
                        yield ((CoreExtension::getAttribute($this->env, $this->source, $context["op"], "doc_num", [], "any", false, false, true, 275)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "doc_num", [], "any", false, false, true, 275), 275, $this->source), "html", null, true)) : ("без №"));
                        yield " • ";
                        yield ((CoreExtension::getAttribute($this->env, $this->source, $context["op"], "doc_date", [], "any", false, false, true, 275)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "doc_date", [], "any", false, false, true, 275), 275, $this->source), "html", null, true)) : ("без дати"));
                        yield " • К-сть: ";
                        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "quantity", [], "any", false, false, true, 275), 275, $this->source), "html", null, true);
                        yield "
                            </option>
                        ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_key'], $context['op'], $context['_parent']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 278
                    yield "                    </select>
                </div>
                <div class=\"warehouse__sum split-operation-doc\">—</div>
            </div>
        ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_key'], $context['row'], $context['_parent']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 283
                yield "    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['candidate'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 284
            yield "</div>
</div>
";
        }
        // line 287
        yield "
";
        // line 288
        if ((((( !Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["differences"] ?? null)) &&  !Twig\Extension\CoreExtension::length($this->env->getCharset(), ((array_key_exists("newProducts", $context)) ? (Twig\Extension\CoreExtension::default(($context["newProducts"] ?? null), [])) : ([])))) &&  !Twig\Extension\CoreExtension::length($this->env->getCharset(), ((array_key_exists("missingProducts", $context)) ? (Twig\Extension\CoreExtension::default(($context["missingProducts"] ?? null), [])) : ([])))) &&  !Twig\Extension\CoreExtension::length($this->env->getCharset(), ((array_key_exists("ambiguousMatches", $context)) ? (Twig\Extension\CoreExtension::default(($context["ambiguousMatches"] ?? null), [])) : ([])))) &&  !Twig\Extension\CoreExtension::length($this->env->getCharset(), ((array_key_exists("splitCandidates", $context)) ? (Twig\Extension\CoreExtension::default(($context["splitCandidates"] ?? null), [])) : ([]))))) {
            // line 289
            yield "<div class=\"alert alert--success\">
    Различий нет. Новых продуктов: ";
            // line 290
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(($context["newCount"] ?? null), 290, $this->source), "html", null, true);
            yield "
</div>
";
        }
        // line 293
        yield "
<div class=\"import__bottom\">

    <button type=\"button\" class=\"button button--nm button--secondary\" id=\"download-differences\">
        Завантажити файл с відмінностями
    </button>

    <div class=\"import__bottom-right\">
        <button type=\"button\" class=\"button button--nm button--secondary\" id=\"cancel-differences\">
            Відмінити
        </button>
        ";
        // line 304
        if ((((Twig\Extension\CoreExtension::length($this->env->getCharset(), ($context["differences"] ?? null)) || Twig\Extension\CoreExtension::length($this->env->getCharset(), ((array_key_exists("newProducts", $context)) ? (Twig\Extension\CoreExtension::default(($context["newProducts"] ?? null), [])) : ([])))) || Twig\Extension\CoreExtension::length($this->env->getCharset(), ((array_key_exists("ambiguousMatches", $context)) ? (Twig\Extension\CoreExtension::default(($context["ambiguousMatches"] ?? null), [])) : ([])))) || Twig\Extension\CoreExtension::length($this->env->getCharset(), ((array_key_exists("splitCandidates", $context)) ? (Twig\Extension\CoreExtension::default(($context["splitCandidates"] ?? null), [])) : ([]))))) {
            // line 305
            yield "        <button type=\"button\" class=\"button button--nm button--brand\" id=\"apply-differences\" data-final-label=\"Зберегти зміни\" data-next-label=\"Далі\">
            ";
            // line 306
            yield (((($context["tabsCount"] ?? null) > 1)) ? ("Далі") : ("Зберегти зміни"));
            yield "
        </button>
        ";
        }
        // line 309
        yield "    </div>";
        // line 310
        yield "</div>";
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
        return array (  822 => 310,  820 => 309,  814 => 306,  811 => 305,  809 => 304,  796 => 293,  790 => 290,  787 => 289,  785 => 288,  782 => 287,  777 => 284,  771 => 283,  761 => 278,  744 => 275,  740 => 274,  736 => 273,  732 => 272,  728 => 271,  723 => 270,  719 => 269,  714 => 267,  710 => 266,  704 => 263,  700 => 262,  693 => 260,  689 => 259,  683 => 256,  679 => 255,  676 => 254,  671 => 253,  667 => 252,  651 => 238,  649 => 237,  646 => 236,  641 => 233,  625 => 230,  621 => 228,  618 => 227,  599 => 224,  594 => 222,  590 => 221,  586 => 220,  582 => 219,  578 => 218,  572 => 214,  554 => 213,  552 => 212,  541 => 208,  534 => 206,  529 => 204,  525 => 203,  521 => 202,  517 => 201,  513 => 200,  509 => 199,  506 => 198,  489 => 197,  481 => 191,  479 => 190,  476 => 189,  471 => 186,  462 => 183,  458 => 182,  454 => 181,  450 => 180,  445 => 178,  441 => 177,  436 => 174,  432 => 173,  416 => 159,  414 => 158,  411 => 157,  406 => 154,  397 => 151,  393 => 150,  389 => 149,  385 => 148,  380 => 146,  376 => 145,  371 => 142,  367 => 141,  351 => 127,  349 => 126,  346 => 125,  341 => 122,  330 => 117,  322 => 116,  314 => 115,  306 => 114,  303 => 113,  295 => 110,  292 => 109,  290 => 108,  286 => 107,  282 => 106,  278 => 105,  271 => 103,  265 => 102,  259 => 101,  253 => 100,  247 => 99,  240 => 95,  236 => 94,  232 => 93,  228 => 92,  224 => 91,  215 => 85,  211 => 84,  207 => 83,  203 => 82,  199 => 81,  195 => 80,  191 => 79,  188 => 78,  184 => 77,  166 => 61,  164 => 60,  161 => 59,  157 => 57,  151 => 54,  142 => 49,  139 => 48,  133 => 45,  124 => 40,  121 => 39,  115 => 36,  106 => 31,  103 => 30,  97 => 27,  88 => 22,  85 => 21,  79 => 18,  72 => 13,  70 => 12,  67 => 11,  65 => 10,  62 => 9,  60 => 8,  58 => 7,  56 => 6,  54 => 5,  52 => 4,  50 => 3,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<input type=\"hidden\" id=\"import-download-report\" value=\"{{ downloadReportEncoded|default('') }}\">

{% set hasDiff = differences|length %}
{% set hasNew = newProducts|default([])|length %}
{% set hasMissing = missingProducts|default([])|length %}
{% set hasAmbiguous = ambiguousMatches|default([])|length %}
{% set hasSplit = splitCandidates|default([])|length %}
{% set tabsCount = (hasDiff ? 1 : 0) + (hasNew ? 1 : 0) + (hasMissing ? 1 : 0) + (hasAmbiguous ? 1 : 0) + (hasSplit ? 1 : 0) %}

{% if tabsCount > 0 %}
<div id=\"import-tabs\" class=\"notes-filter\" style=\"margin: 0 0 14px;\">
    {% if hasDiff %}
        <button type=\"button\" class=\"notes-filter__tab import-tab-btn is-active\"
                data-tab-target=\"differences\"
                data-tab-title=\"Відмінності зі складом\"
                data-tab-subtitle=\"Позиції з різницею у кількості, ціні, сумі або інвентарному номері.\">
            <span class=\"notes-filter__label\">Відмінності</span>
            <span class=\"notes-filter__count label--extra-sm label--secondary\" data-count>{{ differences|length }}</span>
        </button>
    {% endif %}
    {% if hasNew %}
        <button type=\"button\" class=\"notes-filter__tab import-tab-btn {{ not hasDiff ? 'is-active' : '' }}\"
                data-tab-target=\"new\"
                data-tab-title=\"Нові позиції\"
                data-tab-subtitle=\"Товари, яких не було в БД та які будуть додані після підтвердження.\">
            <span class=\"notes-filter__label\">Нові</span>
            <span class=\"notes-filter__count label--extra-sm label--secondary\" data-count>{{ newProducts|length }}</span>
        </button>
    {% endif %}
    {% if hasMissing %}
        <button type=\"button\" class=\"notes-filter__tab import-tab-btn {{ (not hasDiff and not hasNew) ? 'is-active' : '' }}\"
                data-tab-target=\"missing\"
                data-tab-title=\"Позиції, що зникли\"
                data-tab-subtitle=\"Товари, що є в БД, але відсутні у поточному Excel, включно з нульовими залишками.\">
            <span class=\"notes-filter__label\">Відсутні в Excel</span>
            <span class=\"notes-filter__count label--extra-sm label--secondary\" data-count>{{ missingProducts|length }}</span>
        </button>
    {% endif %}
    {% if hasAmbiguous %}
        <button type=\"button\" class=\"notes-filter__tab import-tab-btn {{ (not hasDiff and not hasNew and not hasMissing) ? 'is-active' : '' }}\"
                data-tab-target=\"ambiguous\"
                data-tab-title=\"Ручна перевірка\"
                data-tab-subtitle=\"Оберіть вручну правильний товар у БД для неоднозначних інвентарних номерів.\">
            <span class=\"notes-filter__label\">Ручна перевірка</span>
            <span class=\"notes-filter__count label--extra-sm label--secondary\" data-count>{{ ambiguousMatches|length }}</span>
        </button>
    {% endif %}
    {% if hasSplit %}
        <button type=\"button\" class=\"notes-filter__tab import-tab-btn {{ (not hasDiff and not hasNew and not hasMissing and not hasAmbiguous) ? 'is-active' : '' }}\"
                data-tab-target=\"split\"
                data-tab-title=\"Розподіл операцій\"
                data-tab-subtitle=\"Оберіть для кожної нової позиції операцію приходу, яка має бути до неї прив'язана.\">
            <span class=\"notes-filter__label\">Розподіл операцій</span>
            <span class=\"notes-filter__count label--extra-sm label--secondary\" data-count>{{ splitCandidates|length }}</span>
        </button>
    {% endif %}
</div>
{% endif %}

{% if differences|length %}
<div class=\"import-modal-section\" data-tab-key=\"differences\" data-modal-title=\"Відмінності зі складом\" data-modal-subtitle=\"Позиції з різницею у кількості, ціні, сумі або інвентарному номері.\">
<div class=\"table\" style=\"margin-bottom: 16px; border-radius: var(--radius-md);\">
    <div class=\"warehouse__item table-title\" style=\"background: var(--bg-box); border-radius: 0; width: 100%;\">
        <div class=\"warehouse__checkbox\">
            <label for=\"\" class=\"checkbox\"><input type=\"checkbox\" id=\"select-all-diffs\" /></label>
        </div>
        <div class=\"warehouse__left\">
            <div class=\"warehouse__name\">Найменування</div>
            <div class=\"warehouse__number\">Номенклатурний №</div>
        </div>
        <div class=\"warehouse__unit\">К-сть (Склад / Excel)</div>
        <div class=\"warehouse__price\">Ціна (Склад / Excel)</div>
        <div class=\"warehouse__quantity\">Сума (Склад / Excel)</div>
        <div class=\"warehouse__sum\">Статус</div>
    </div>

    {% for diff in differences %}
        <div class=\"warehouse__item table-item diff-row\"
             data-name=\"{{ diff.name }}\"
             data-current-quantity=\"{{ diff.current_quantity }}\"
             data-excel-quantity=\"{{ diff.excel_quantity }}\"
             data-current-price=\"{{ diff.price }}\"
             data-excel-price=\"{{ diff.excel_price }}\"
             data-current-sum=\"{{ diff.sum }}\"
             data-excel-sum=\"{{ diff.excel_sum }}\">
            <div class=\"warehouse__checkbox\">
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
                <input type=\"hidden\" name=\"updates[{{ diff.id }}][excel_inv_number]\" value=\"{{ diff.excel_inv_number|default(diff.inv_number) }}\">
                <input type=\"hidden\" name=\"updates[{{ diff.id }}][quantity]\" value=\"{{ diff.excel_quantity }}\">
                <input type=\"hidden\" name=\"updates[{{ diff.id }}][price]\" value=\"{{ diff.excel_price }}\">
                <input type=\"hidden\" name=\"updates[{{ diff.id }}][sum]\" value=\"{{ diff.excel_sum }}\">
            </div>
            <div class=\"warehouse__left {{ diff.inv_changed ? 'import__cell--diff' : '' }}\">
                <div class=\"warehouse__name\">{{ diff.name }}</div>
                <div class=\"warehouse__number\">{{ diff.inv_number }}</div>
                {% if diff.inv_changed and diff.excel_inv_number is not empty %}
                    <div style=\"font-size:12px; color: var(--text-secondary);\">
                        {{ diff.inv_number }} → {{ diff.excel_inv_number }}
                    </div>
                {% endif %}
            </div>
            <div class=\"warehouse__unit {{ diff.current_quantity != diff.excel_quantity ? 'import__cell--diff' : '' }}\">{{ diff.current_quantity }} → {{ diff.excel_quantity }}</div>
            <div class=\"warehouse__price {{ diff.price != diff.excel_price ? 'import__cell--diff' : '' }}\">{{ diff.price }} → {{ diff.excel_price }}</div>
            <div class=\"warehouse__quantity {{ diff.sum != diff.excel_sum ? 'import__cell--diff' : '' }}\">{{ diff.sum }} → {{ diff.excel_sum }}</div>
            <div class=\"warehouse__sum {{ (diff.current_quantity != diff.excel_quantity or diff.price != diff.excel_price or diff.sum != diff.excel_sum or diff.inv_changed) ? 'import__cell--diff' : '' }}\">
                Є зміни
            </div>
        </div>
    {% endfor %}
</div>
</div>
{% endif %}

{% if newProducts|default([])|length %}
<div class=\"import-modal-section\" data-tab-key=\"new\" data-modal-title=\"Нові позиції\" data-modal-subtitle=\"Товари, яких не було в БД та які будуть додані після підтвердження.\">
<div class=\"table\" style=\"margin-bottom: 16px; border-radius: var(--radius-md);\">
    <div class=\"warehouse__item table-title\" style=\"background: var(--bg-box); border-radius: 0; width: 100%;\">
        <div class=\"warehouse__checkbox\"></div>
        <div class=\"warehouse__left\">
            <div class=\"warehouse__name\">Наименование</div>
            <div class=\"warehouse__number\">Номенклатурный №</div>
        </div>
        <div class=\"warehouse__unit\">Ед.измерения</div>
        <div class=\"warehouse__price\">Цена</div>
        <div class=\"warehouse__quantity\">Количество</div>
        <div class=\"warehouse__sum\">Сумма</div>
    </div>

    {% for item in newProducts %}
        <div class=\"warehouse__item table-item\">
            <div class=\"warehouse__checkbox\"></div>
            <div class=\"warehouse__left\">
                <div class=\"warehouse__name\">{{ item.name }}</div>
                <div class=\"warehouse__number\">{{ item.inv_number ?: '-' }}</div>
            </div>
            <div class=\"warehouse__unit\">{{ item.unit ?: '-' }}</div>
            <div class=\"warehouse__price\">{{ item.excel_price }}</div>
            <div class=\"warehouse__quantity\">{{ item.excel_quantity }}</div>
            <div class=\"warehouse__sum\">{{ item.excel_sum }}</div>
        </div>
    {% endfor %}
</div>
</div>
{% endif %}

{% if missingProducts|default([])|length %}
<div class=\"import-modal-section\" data-tab-key=\"missing\" data-modal-title=\"Позиції, що зникли\" data-modal-subtitle=\"Товари, що є в БД, але відсутні у поточному Excel, включно з нульовими залишками.\">
<div class=\"table\" style=\"margin-bottom: 16px; border-radius: var(--radius-md);\">
    <div class=\"warehouse__item table-title\" style=\"background: var(--bg-box); border-radius: 0; width: 100%;\">
        <div class=\"warehouse__checkbox\"></div>
        <div class=\"warehouse__left\">
            <div class=\"warehouse__name\">Наименование</div>
            <div class=\"warehouse__number\">Номенклатурный №</div>
        </div>
        <div class=\"warehouse__unit\">Ед.измерения</div>
        <div class=\"warehouse__price\">Цена</div>
        <div class=\"warehouse__quantity\">Количество</div>
        <div class=\"warehouse__sum\">Сумма</div>
    </div>

    {% for item in missingProducts %}
        <div class=\"warehouse__item table-item\">
            <div class=\"warehouse__checkbox\"></div>
            <div class=\"warehouse__left\">
                <div class=\"warehouse__name\">{{ item.name }}</div>
                <div class=\"warehouse__number\">{{ item.inv_number ?: '-' }}</div>
            </div>
            <div class=\"warehouse__unit\">{{ item.unit ?: '-' }}</div>
            <div class=\"warehouse__price\">{{ item.current_price }}</div>
            <div class=\"warehouse__quantity\">{{ item.current_quantity }}</div>
            <div class=\"warehouse__sum\">{{ item.current_sum }}</div>
        </div>
    {% endfor %}
</div>
</div>
{% endif %}

{% if ambiguousMatches|default([])|length %}
<div class=\"import-modal-section\" data-tab-key=\"ambiguous\" data-modal-title=\"Ручна перевірка\" data-modal-subtitle=\"Оберіть вручну правильний товар у БД для неоднозначних інвентарних номерів.\">
<div class=\"import table\" style=\"margin-bottom: 16px;\">
    <div class=\"import__item table-title\">
        <div class=\"import__name\" style=\"grid-column: 1 / -1;\">Потрібна ручна перевірка (неоднозначне співпадіння) — оберіть відповідний товар у БД</div>
    </div>

    {% for item in ambiguousMatches %}
        <div class=\"import__item table-item ambiguous-item\"
             data-index=\"{{ loop.index0 }}\"
             data-excel-name=\"{{ item.excel_name|e }}\"
             data-excel-inv=\"{{ item.excel_inv_number|e }}\"
             data-excel-quantity=\"{{ item.excel_quantity|default(0) }}\"
             data-excel-price=\"{{ item.excel_price|default(0) }}\"
             data-excel-sum=\"{{ item.excel_sum|default(0) }}\">
            <div class=\"import__name\">
                {{ item.excel_name }} ({{ item.excel_inv_number }})
                <div style=\"font-size:12px; color: var(--text-secondary);\">
                    К-сть: {{ item.excel_quantity|default(0) }}, Ціна: {{ item.excel_price|default(0) }}, Сума: {{ item.excel_sum|default(0) }}
                </div>
            </div>
            <div class=\"import__quantity\" style=\"white-space: normal;\">
                {% if item.candidates|length %}
                    {% for candidate in item.candidates %}
                        <label class=\"checkbox\" style=\"display:block; margin-bottom: 6px;\">
                            <input
                                type=\"radio\"
                                class=\"ambiguous-choice\"
                                name=\"ambiguous_choice_{{ loop.parent.index0 }}\"
                                value=\"{{ candidate.id }}\"
                                data-product-id=\"{{ candidate.id }}\"
                                data-product-name=\"{{ candidate.name|e }}\"
                                data-product-inv=\"{{ candidate.inv_number|e }}\"
                            />
                            <span>{{ candidate.name }} ({{ candidate.inv_number }})</span>
                        </label>
                    {% endfor %}
                {% else %}
                    <div>Немає кандидатів</div>
                {% endif %}
            </div>
        </div>
    {% endfor %}
</div>
</div>
{% endif %}

{% if splitCandidates|default([])|length %}
<div class=\"import-modal-section\" data-tab-key=\"split\" data-modal-title=\"Розподіл операцій\" data-modal-subtitle=\"Оберіть, яку операцію застосувати до кожної нової позиції після розділення.\">
<div class=\"table\" style=\"margin-bottom: 16px; border-radius: var(--radius-md);\">
    <div class=\"warehouse__item table-title\" style=\"background: var(--bg-box); border-radius: 0; width: 100%;\">
        <div class=\"warehouse__checkbox\"></div>
        <div class=\"warehouse__left\">
            <div class=\"warehouse__name\">Нова позиція</div>
            <div class=\"warehouse__number\">Базова позиція</div>
        </div>
        <div class=\"warehouse__unit\">К-сть</div>
        <div class=\"warehouse__price\">Ціна</div>
        <div class=\"warehouse__quantity\">Операція</div>
        <div class=\"warehouse__sum\">Документ</div>
    </div>

    {% for candidate in splitCandidates %}
        {% for row in candidate.rows %}
            <div class=\"warehouse__item table-item split-item\"
                 data-base-product-id=\"{{ candidate.base_product_id }}\"
                 data-excel-inv=\"{{ row.excel_inv_number|e }}\">
                <div class=\"warehouse__checkbox\"></div>
                <div class=\"warehouse__left\">
                    <div class=\"warehouse__name\">{{ candidate.base_name }}</div>
                    <div class=\"warehouse__number\">{{ candidate.base_inv_number }} → {{ row.excel_inv_number }}</div>
                </div>
                <div class=\"warehouse__unit\">{{ row.excel_quantity }}</div>
                <div class=\"warehouse__price\">{{ row.excel_price }}</div>
                <div class=\"warehouse__quantity\" style=\"white-space: normal; width: 100%;\">
                    <select class=\"split-operation-select\" style=\"width:100%; min-width: 240px;\"
                            data-base-product-id=\"{{ candidate.base_product_id }}\"
                            data-excel-inv=\"{{ row.excel_inv_number|e }}\">
                        <option value=\"\">Оберіть операцію</option>
                        {% for op in candidate.operations %}
                            <option value=\"{{ op.operation_id }}\"
                                data-op-qty=\"{{ op.quantity }}\"
                                data-op-sum=\"{{ op.sum }}\"
                                data-op-doc=\"{{ op.doc_num ?: '-' }}\"
                                data-op-date=\"{{ op.doc_date ?: '-' }}\">
                                #{{ op.operation_id }} • {{ op.operation_type }} • {{ op.doc_num ?: 'без №' }} • {{ op.doc_date ?: 'без дати' }} • К-сть: {{ op.quantity }}
                            </option>
                        {% endfor %}
                    </select>
                </div>
                <div class=\"warehouse__sum split-operation-doc\">—</div>
            </div>
        {% endfor %}
    {% endfor %}
</div>
</div>
{% endif %}

{% if not differences|length and not newProducts|default([])|length and not missingProducts|default([])|length and not ambiguousMatches|default([])|length and not splitCandidates|default([])|length %}
<div class=\"alert alert--success\">
    Различий нет. Новых продуктов: {{ newCount }}
</div>
{% endif %}

<div class=\"import__bottom\">

    <button type=\"button\" class=\"button button--nm button--secondary\" id=\"download-differences\">
        Завантажити файл с відмінностями
    </button>

    <div class=\"import__bottom-right\">
        <button type=\"button\" class=\"button button--nm button--secondary\" id=\"cancel-differences\">
            Відмінити
        </button>
        {% if differences|length or newProducts|default([])|length or ambiguousMatches|default([])|length or splitCandidates|default([])|length %}
        <button type=\"button\" class=\"button button--nm button--brand\" id=\"apply-differences\" data-final-label=\"Зберегти зміни\" data-next-label=\"Далі\">
            {{ tabsCount > 1 ? 'Далі' : 'Зберегти зміни' }}
        </button>
        {% endif %}
    </div>{# import__bottom-right #}
</div>{# import__bottom #}", "C:\\OSPanel\\domains\\inventpro-test\\themes\\invent-pro\\partials\\modals\\modal_import_result.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["set" => 3, "if" => 10, "for" => 77];
        static $filters = ["escape" => 1, "default" => 1, "length" => 3, "e" => 200];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['set', 'if', 'for'],
                ['escape', 'default', 'length', 'e'],
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
