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

/* C:\OSPanel\domains\inventpro-test\themes\invent-pro\partials\notes\item-single.htm */
class __TwigTemplate_cef4994a366a57d9a25475e21d3e938b extends Template
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
        yield "<article class=\"note-single\">
    <header class=\"note-single__header\">
        ";
        // line 3
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "status_label", [], "any", false, false, true, 3) == "В розробці")) {
            // line 4
            yield "            <div class=\"note-status ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "status_class", [], "any", false, false, true, 4), 4, $this->source), "html", null, true);
            yield "\">
                <svg width=\"15\" height=\"15\" viewBox=\"0 0 15 15\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                    <circle cx=\"7.5\" cy=\"7.5\" r=\"7\" stroke=\"currentColor\" stroke-dasharray=\"3 2\"/>
                </svg>
                ";
            // line 8
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "status_label", [], "any", false, false, true, 8), 8, $this->source), "html", null, true);
            yield "
            </div>
        ";
        } elseif ((CoreExtension::getAttribute($this->env, $this->source,         // line 10
($context["note"] ?? null), "status_label", [], "any", false, false, true, 10) == "Документи готові")) {
            // line 11
            yield "            <div class=\"note-status ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "status_class", [], "any", false, false, true, 11), 11, $this->source), "html", null, true);
            yield "\">
                <svg width=\"15\" height=\"15\" viewBox=\"0 0 15 15\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                    <circle cx=\"7.5\" cy=\"7.5\" r=\"7\" stroke=\"currentColor\" stroke-dasharray=\"3 2\"/>
                    <mask id=\"path-2-inside-1_106_1232\" fill=\"white\">
                        <path d=\"M7.49998 2.83342C8.73766 2.83342 9.92464 3.32508 10.7998 4.20025C11.675 5.07542 12.1666 6.2624 12.1666 7.50008C12.1666 8.73776 11.675 9.92474 10.7998 10.7999C9.92464 11.6751 8.73766 12.1667 7.49998 12.1667L7.49998 7.50008V2.83342Z\"/>
                    </mask>
                    <path d=\"M7.49998 2.83342C8.73766 2.83342 9.92464 3.32508 10.7998 4.20025C11.675 5.07542 12.1666 6.2624 12.1666 7.50008C12.1666 8.73776 11.675 9.92474 10.7998 10.7999C9.92464 11.6751 8.73766 12.1667 7.49998 12.1667L7.49998 7.50008V2.83342Z\" fill=\"currentColor\" stroke=\"currentColor\" stroke-width=\"2\" mask=\"url(#path-2-inside-1_106_1232)\"/>
                </svg>
                ";
            // line 19
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "status_label", [], "any", false, false, true, 19), 19, $this->source), "html", null, true);
            yield "
            </div>
        ";
        } elseif ((CoreExtension::getAttribute($this->env, $this->source,         // line 21
($context["note"] ?? null), "status_label", [], "any", false, false, true, 21) == "В бухгалтерії")) {
            // line 22
            yield "            <div class=\"note-status ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "status_class", [], "any", false, false, true, 22), 22, $this->source), "html", null, true);
            yield "\">
                <svg width=\"15\" height=\"15\" viewBox=\"0 0 15 15\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                    <circle cx=\"7.5\" cy=\"7.5\" r=\"7\" stroke=\"currentColor\" stroke-dasharray=\"3 2\"/>
                    <path d=\"M7.49998 2.83342C8.42296 2.83342 9.32521 3.10711 10.0926 3.61989C10.8601 4.13267 11.4582 4.8615 11.8114 5.71423C12.1646 6.56695 12.257 7.50526 12.077 8.4105C11.8969 9.31575 11.4525 10.1473 10.7998 10.7999C10.1472 11.4526 9.31565 11.897 8.4104 12.0771C7.50516 12.2571 6.56685 12.1647 5.71412 11.8115C4.8614 11.4583 4.13257 10.8602 3.61979 10.0927C3.10701 9.32531 2.83331 8.42306 2.83331 7.50008H7.49998V2.83342Z\" fill=\"currentColor\"/>
                </svg>
                ";
            // line 27
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "status_label", [], "any", false, false, true, 27), 27, $this->source), "html", null, true);
            yield "
            </div>
        ";
        } elseif ((CoreExtension::getAttribute($this->env, $this->source,         // line 29
($context["note"] ?? null), "status_label", [], "any", false, false, true, 29) == "Виконано")) {
            // line 30
            yield "            <div class=\"note-status ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "status_class", [], "any", false, false, true, 30), 30, $this->source), "html", null, true);
            yield "\">
                <svg width=\"15\" height=\"15\" viewBox=\"0 0 15 15\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                    <circle cx=\"7.5\" cy=\"7.5\" r=\"7\" stroke=\"currentColor\" stroke-dasharray=\"3 2\"/>
                    <circle cx=\"7.50001\" cy=\"7.50008\" r=\"4.66667\" transform=\"rotate(-90 7.50001 7.50008)\" fill=\"currentColor\"/>
                </svg>
                ";
            // line 35
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "status_label", [], "any", false, false, true, 35), 35, $this->source), "html", null, true);
            yield "
            </div>
        ";
        }
        // line 38
        yield "
        <h2 class=\"note-single__title\">";
        // line 39
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "title", [], "any", false, false, true, 39), 39, $this->source), "html", null, true);
        yield "</h2>
        <p class=\"note-single__description\">";
        // line 40
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "description", [], "any", false, false, true, 40), 40, $this->source), "html", null, true);
        yield "</p>

        <div class=\"note-tabs\">
            <button type=\"button\" class=\"note-tab note-tab--active\" data-tab=\"info\">";
        // line 43
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "tab_info_label", [], "any", false, false, true, 43), 43, $this->source), "html", null, true);
        yield "</button>
            <button type=\"button\" class=\"note-tab\" data-tab=\"history\">";
        // line 44
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "tab_history_label", [], "any", false, false, true, 44), 44, $this->source), "html", null, true);
        yield "</button>
        </div>
    </header>

    <div class=\"note-single__content\">
        <div class=\"note-card__tab-content note-card__tab-content--info is-active\" data-tab-content=\"info\">
            ";
        // line 50
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "show_products", [], "any", false, false, true, 50)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 51
            yield "                <section class=\"note-section\">
                    <div class=\"note-section__title\">";
            // line 52
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "products_title", [], "any", false, false, true, 52), 52, $this->source), "html", null, true);
            yield "</div>
                    <ul class=\"note-products\">
                        ";
            // line 54
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "products", [], "any", false, false, true, 54));
            foreach ($context['_seq'] as $context["_key"] => $context["product"]) {
                // line 55
                yield "                            <li class=\"note-products__item\">";
                yield ((CoreExtension::getAttribute($this->env, $this->source, $context["product"], "name", [], "any", false, false, true, 55)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["product"], "name", [], "any", false, false, true, 55), 55, $this->source), "html", null, true)) : ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["product"], "inv_number", [], "any", false, false, true, 55), 55, $this->source), "html", null, true)));
                yield "</li>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['product'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 57
            yield "                    </ul>
                </section>
            ";
        }
        // line 60
        yield "
            ";
        // line 61
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "show_operations", [], "any", false, false, true, 61)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 62
            yield "                <section class=\"note-section\">
                    <div class=\"note-section__title\">";
            // line 63
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "operations_title", [], "any", false, false, true, 63), 63, $this->source), "html", null, true);
            yield "</div>
                    <div class=\"note-operations\">
                        ";
            // line 65
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "operations", [], "any", false, false, true, 65));
            foreach ($context['_seq'] as $context["_key"] => $context["op"]) {
                // line 66
                yield "                            <div class=\"note-op\">
                                <div class=\"note-op__status ";
                // line 67
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "status_class", [], "any", false, false, true, 67), 67, $this->source), "html", null, true);
                yield "\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "status_label", [], "any", false, false, true, 67), 67, $this->source), "html", null, true);
                yield "</div>
                                ";
                // line 68
                if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["op"], "can_toggle_accounting", [], "any", false, false, true, 68)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    // line 69
                    yield "                                    <button
                                        type=\"button\"
                                        class=\"note-op__toggle\"
                                        data-request=\"onToggleAccountingStatus\"
                                        data-request-data=\"operation_id: ";
                    // line 73
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "id", [], "any", false, false, true, 73), 73, $this->source), "html", null, true);
                    yield ", note_id: ";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "id", [], "any", false, false, true, 73), 73, $this->source), "html", null, true);
                    yield "\"
                                        data-request-flash
                                        data-request-lock>
                                        ";
                    // line 76
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "toggle_label", [], "any", false, false, true, 76), 76, $this->source), "html", null, true);
                    yield "
                                    </button>
                                ";
                }
                // line 79
                yield "                                <div class=\"note-op__title\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "title", [], "any", false, false, true, 79), 79, $this->source), "html", null, true);
                yield "</div>
                                <div class=\"note-op__date\">";
                // line 80
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "date_label", [], "any", false, false, true, 80), 80, $this->source), "html", null, true);
                yield "</div>
                                ";
                // line 81
                if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["op"], "show_products", [], "any", false, false, true, 81)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    // line 82
                    yield "                                    <ul class=\"note-op__products\">
                                        ";
                    // line 83
                    $context['_parent'] = $context;
                    $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "products", [], "any", false, false, true, 83));
                    foreach ($context['_seq'] as $context["_key"] => $context["product"]) {
                        // line 84
                        yield "                                            <li class=\"note-op__product\">";
                        yield ((CoreExtension::getAttribute($this->env, $this->source, $context["product"], "name", [], "any", false, false, true, 84)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["product"], "name", [], "any", false, false, true, 84), 84, $this->source), "html", null, true)) : ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["product"], "inv_number", [], "any", false, false, true, 84), 84, $this->source), "html", null, true)));
                        yield "</li>
                                        ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_key'], $context['product'], $context['_parent']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 86
                    yield "                                    </ul>
                                ";
                }
                // line 88
                yield "                            </div>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['op'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 90
            yield "                    </div>
                </section>
            ";
        }
        // line 93
        yield "        </div>

        <div class=\"note-card__tab-content note-card__tab-content--history\" data-tab-content=\"history\">
            ";
        // line 96
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "show_timeline", [], "any", false, false, true, 96)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 97
            yield "                <section class=\"note-section\">
                    <div class=\"note-section__title\">";
            // line 98
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "timeline_title", [], "any", false, false, true, 98), 98, $this->source), "html", null, true);
            yield "</div>
                    <ul class=\"note-timeline\">
                        ";
            // line 100
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "timeline", [], "any", false, false, true, 100));
            foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                // line 101
                yield "                            <li class=\"note-timeline__item ";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "status_class", [], "any", false, false, true, 101), 101, $this->source), "html", null, true);
                yield "\">
                                <div class=\"note-timeline__label\">";
                // line 102
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "label", [], "any", false, false, true, 102), 102, $this->source), "html", null, true);
                yield "</div>
                                <div class=\"note-timeline__date\">";
                // line 103
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "date_label", [], "any", false, false, true, 103), 103, $this->source), "html", null, true);
                yield "</div>
                            </li>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['item'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 106
            yield "                    </ul>
                </section>
            ";
        }
        // line 109
        yield "        </div>
    </div>

    <footer class=\"note-single__actions\">
        ";
        // line 113
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "is_single", [], "any", false, false, true, 113)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 114
            yield "            <a href=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "create_operation_url", [], "any", false, false, true, 114), 114, $this->source), "html", null, true);
            yield "\" class=\"button button--nm button--brand\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "create_operation_label", [], "any", false, false, true, 114), 114, $this->source), "html", null, true);
            yield "</a>
        ";
        } else {
            // line 116
            yield "            <a href=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "action_url", [], "any", false, false, true, 116), 116, $this->source), "html", null, true);
            yield "\" class=\"button button--nm button--brand\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "action_label", [], "any", false, false, true, 116), 116, $this->source), "html", null, true);
            yield "</a>
        ";
        }
        // line 118
        yield "    </footer>
</article>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\inventpro-test\\themes\\invent-pro\\partials\\notes\\item-single.htm";
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
        return array (  326 => 118,  318 => 116,  310 => 114,  308 => 113,  302 => 109,  297 => 106,  288 => 103,  284 => 102,  279 => 101,  275 => 100,  270 => 98,  267 => 97,  265 => 96,  260 => 93,  255 => 90,  248 => 88,  244 => 86,  235 => 84,  231 => 83,  228 => 82,  226 => 81,  222 => 80,  217 => 79,  211 => 76,  203 => 73,  197 => 69,  195 => 68,  189 => 67,  186 => 66,  182 => 65,  177 => 63,  174 => 62,  172 => 61,  169 => 60,  164 => 57,  155 => 55,  151 => 54,  146 => 52,  143 => 51,  141 => 50,  132 => 44,  128 => 43,  122 => 40,  118 => 39,  115 => 38,  109 => 35,  100 => 30,  98 => 29,  93 => 27,  84 => 22,  82 => 21,  77 => 19,  65 => 11,  63 => 10,  58 => 8,  50 => 4,  48 => 3,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<article class=\"note-single\">
    <header class=\"note-single__header\">
        {% if note.status_label == 'В розробці' %}
            <div class=\"note-status {{ note.status_class }}\">
                <svg width=\"15\" height=\"15\" viewBox=\"0 0 15 15\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                    <circle cx=\"7.5\" cy=\"7.5\" r=\"7\" stroke=\"currentColor\" stroke-dasharray=\"3 2\"/>
                </svg>
                {{ note.status_label }}
            </div>
        {% elseif note.status_label == 'Документи готові' %}
            <div class=\"note-status {{ note.status_class }}\">
                <svg width=\"15\" height=\"15\" viewBox=\"0 0 15 15\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                    <circle cx=\"7.5\" cy=\"7.5\" r=\"7\" stroke=\"currentColor\" stroke-dasharray=\"3 2\"/>
                    <mask id=\"path-2-inside-1_106_1232\" fill=\"white\">
                        <path d=\"M7.49998 2.83342C8.73766 2.83342 9.92464 3.32508 10.7998 4.20025C11.675 5.07542 12.1666 6.2624 12.1666 7.50008C12.1666 8.73776 11.675 9.92474 10.7998 10.7999C9.92464 11.6751 8.73766 12.1667 7.49998 12.1667L7.49998 7.50008V2.83342Z\"/>
                    </mask>
                    <path d=\"M7.49998 2.83342C8.73766 2.83342 9.92464 3.32508 10.7998 4.20025C11.675 5.07542 12.1666 6.2624 12.1666 7.50008C12.1666 8.73776 11.675 9.92474 10.7998 10.7999C9.92464 11.6751 8.73766 12.1667 7.49998 12.1667L7.49998 7.50008V2.83342Z\" fill=\"currentColor\" stroke=\"currentColor\" stroke-width=\"2\" mask=\"url(#path-2-inside-1_106_1232)\"/>
                </svg>
                {{ note.status_label }}
            </div>
        {% elseif note.status_label == 'В бухгалтерії' %}
            <div class=\"note-status {{ note.status_class }}\">
                <svg width=\"15\" height=\"15\" viewBox=\"0 0 15 15\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                    <circle cx=\"7.5\" cy=\"7.5\" r=\"7\" stroke=\"currentColor\" stroke-dasharray=\"3 2\"/>
                    <path d=\"M7.49998 2.83342C8.42296 2.83342 9.32521 3.10711 10.0926 3.61989C10.8601 4.13267 11.4582 4.8615 11.8114 5.71423C12.1646 6.56695 12.257 7.50526 12.077 8.4105C11.8969 9.31575 11.4525 10.1473 10.7998 10.7999C10.1472 11.4526 9.31565 11.897 8.4104 12.0771C7.50516 12.2571 6.56685 12.1647 5.71412 11.8115C4.8614 11.4583 4.13257 10.8602 3.61979 10.0927C3.10701 9.32531 2.83331 8.42306 2.83331 7.50008H7.49998V2.83342Z\" fill=\"currentColor\"/>
                </svg>
                {{ note.status_label }}
            </div>
        {% elseif note.status_label == 'Виконано' %}
            <div class=\"note-status {{ note.status_class }}\">
                <svg width=\"15\" height=\"15\" viewBox=\"0 0 15 15\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                    <circle cx=\"7.5\" cy=\"7.5\" r=\"7\" stroke=\"currentColor\" stroke-dasharray=\"3 2\"/>
                    <circle cx=\"7.50001\" cy=\"7.50008\" r=\"4.66667\" transform=\"rotate(-90 7.50001 7.50008)\" fill=\"currentColor\"/>
                </svg>
                {{ note.status_label }}
            </div>
        {% endif %}

        <h2 class=\"note-single__title\">{{ note.title }}</h2>
        <p class=\"note-single__description\">{{ note.description }}</p>

        <div class=\"note-tabs\">
            <button type=\"button\" class=\"note-tab note-tab--active\" data-tab=\"info\">{{ note.tab_info_label }}</button>
            <button type=\"button\" class=\"note-tab\" data-tab=\"history\">{{ note.tab_history_label }}</button>
        </div>
    </header>

    <div class=\"note-single__content\">
        <div class=\"note-card__tab-content note-card__tab-content--info is-active\" data-tab-content=\"info\">
            {% if note.show_products %}
                <section class=\"note-section\">
                    <div class=\"note-section__title\">{{ note.products_title }}</div>
                    <ul class=\"note-products\">
                        {% for product in note.products %}
                            <li class=\"note-products__item\">{{ product.name ?: product.inv_number }}</li>
                        {% endfor %}
                    </ul>
                </section>
            {% endif %}

            {% if note.show_operations %}
                <section class=\"note-section\">
                    <div class=\"note-section__title\">{{ note.operations_title }}</div>
                    <div class=\"note-operations\">
                        {% for op in note.operations %}
                            <div class=\"note-op\">
                                <div class=\"note-op__status {{ op.status_class }}\">{{ op.status_label }}</div>
                                {% if op.can_toggle_accounting %}
                                    <button
                                        type=\"button\"
                                        class=\"note-op__toggle\"
                                        data-request=\"onToggleAccountingStatus\"
                                        data-request-data=\"operation_id: {{ op.id }}, note_id: {{ note.id }}\"
                                        data-request-flash
                                        data-request-lock>
                                        {{ op.toggle_label }}
                                    </button>
                                {% endif %}
                                <div class=\"note-op__title\">{{ op.title }}</div>
                                <div class=\"note-op__date\">{{ op.date_label }}</div>
                                {% if op.show_products %}
                                    <ul class=\"note-op__products\">
                                        {% for product in op.products %}
                                            <li class=\"note-op__product\">{{ product.name ?: product.inv_number }}</li>
                                        {% endfor %}
                                    </ul>
                                {% endif %}
                            </div>
                        {% endfor %}
                    </div>
                </section>
            {% endif %}
        </div>

        <div class=\"note-card__tab-content note-card__tab-content--history\" data-tab-content=\"history\">
            {% if note.show_timeline %}
                <section class=\"note-section\">
                    <div class=\"note-section__title\">{{ note.timeline_title }}</div>
                    <ul class=\"note-timeline\">
                        {% for item in note.timeline %}
                            <li class=\"note-timeline__item {{ item.status_class }}\">
                                <div class=\"note-timeline__label\">{{ item.label }}</div>
                                <div class=\"note-timeline__date\">{{ item.date_label }}</div>
                            </li>
                        {% endfor %}
                    </ul>
                </section>
            {% endif %}
        </div>
    </div>

    <footer class=\"note-single__actions\">
        {% if note.is_single %}
            <a href=\"{{ note.create_operation_url }}\" class=\"button button--nm button--brand\">{{ note.create_operation_label }}</a>
        {% else %}
            <a href=\"{{ note.action_url }}\" class=\"button button--nm button--brand\">{{ note.action_label }}</a>
        {% endif %}
    </footer>
</article>", "C:\\OSPanel\\domains\\inventpro-test\\themes\\invent-pro\\partials\\notes\\item-single.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["if" => 3, "for" => 54];
        static $filters = ["escape" => 4];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['if', 'for'],
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
