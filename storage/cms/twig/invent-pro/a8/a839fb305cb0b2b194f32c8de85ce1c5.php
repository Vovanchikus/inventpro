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

/* C:\OSPanel\domains\inventpro\themes\invent-pro\partials\notes\item.htm */
class __TwigTemplate_0a6a33a75460cd22678e49caf435f591 extends Template
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
        yield "<article class=\"note-card\" id=\"note-card-";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "id", [], "any", false, false, true, 1), 1, $this->source), "html", null, true);
        yield "\" data-status-key=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "filter_status_key", [], "any", false, false, true, 1), 1, $this->source), "html", null, true);
        yield "\" data-created-at=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "created_at_ts", [], "any", false, false, true, 1), 1, $this->source), "html", null, true);
        yield "\">
    <div class=\"note-card__top\">
        <div class=\"note-card__header\">
            ";
        // line 4
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "status_label", [], "any", false, false, true, 4) == "В розробці")) {
            // line 5
            yield "                <div class=\"note-status ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "status_class", [], "any", false, false, true, 5), 5, $this->source), "html", null, true);
            yield "\">
                    <svg width=\"15\" height=\"15\" viewBox=\"0 0 15 15\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                        <circle cx=\"7.5\" cy=\"7.5\" r=\"7\" stroke=\"currentColor\" stroke-dasharray=\"3 2\"/>
                    </svg>
                    ";
            // line 9
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "status_label", [], "any", false, false, true, 9), 9, $this->source), "html", null, true);
            yield "
                </div>";
            // line 11
            yield "            ";
        } elseif ((CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "status_label", [], "any", false, false, true, 11) == "Документи готові")) {
            // line 12
            yield "                <div class=\"note-status ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "status_class", [], "any", false, false, true, 12), 12, $this->source), "html", null, true);
            yield "\">
                    <svg width=\"15\" height=\"15\" viewBox=\"0 0 15 15\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                        <circle cx=\"7.5\" cy=\"7.5\" r=\"7\" stroke=\"currentColor\" stroke-dasharray=\"3 2\"/>
                        <mask id=\"path-2-inside-1_106_1232\" fill=\"white\">
                        <path d=\"M7.49998 2.83342C8.73766 2.83342 9.92464 3.32508 10.7998 4.20025C11.675 5.07542 12.1666 6.2624 12.1666 7.50008C12.1666 8.73776 11.675 9.92474 10.7998 10.7999C9.92464 11.6751 8.73766 12.1667 7.49998 12.1667L7.49998 7.50008V2.83342Z\"/>
                        </mask>
                        <path d=\"M7.49998 2.83342C8.73766 2.83342 9.92464 3.32508 10.7998 4.20025C11.675 5.07542 12.1666 6.2624 12.1666 7.50008C12.1666 8.73776 11.675 9.92474 10.7998 10.7999C9.92464 11.6751 8.73766 12.1667 7.49998 12.1667L7.49998 7.50008V2.83342Z\" fill=\"currentColor\" stroke=\"currentColor\" stroke-width=\"2\" mask=\"url(#path-2-inside-1_106_1232)\"/>
                    </svg>
                    ";
            // line 20
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "status_label", [], "any", false, false, true, 20), 20, $this->source), "html", null, true);
            yield "
                </div>";
            // line 22
            yield "            ";
        } elseif ((CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "status_label", [], "any", false, false, true, 22) == "В бухгалтерії")) {
            // line 23
            yield "                <div class=\"note-status ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "status_class", [], "any", false, false, true, 23), 23, $this->source), "html", null, true);
            yield "\">
                    <svg width=\"15\" height=\"15\" viewBox=\"0 0 15 15\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                        <circle cx=\"7.5\" cy=\"7.5\" r=\"7\" stroke=\"currentColor\" stroke-dasharray=\"3 2\"/>
                        <path d=\"M7.49998 2.83342C8.42296 2.83342 9.32521 3.10711 10.0926 3.61989C10.8601 4.13267 11.4582 4.8615 11.8114 5.71423C12.1646 6.56695 12.257 7.50526 12.077 8.4105C11.8969 9.31575 11.4525 10.1473 10.7998 10.7999C10.1472 11.4526 9.31565 11.897 8.4104 12.0771C7.50516 12.2571 6.56685 12.1647 5.71412 11.8115C4.8614 11.4583 4.13257 10.8602 3.61979 10.0927C3.10701 9.32531 2.83331 8.42306 2.83331 7.50008H7.49998V2.83342Z\" fill=\"currentColor\"/>
                    </svg>
                    ";
            // line 28
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "status_label", [], "any", false, false, true, 28), 28, $this->source), "html", null, true);
            yield "
                </div>";
            // line 30
            yield "            ";
        } elseif ((CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "status_label", [], "any", false, false, true, 30) == "Виконано")) {
            // line 31
            yield "                <div class=\"note-status ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "status_class", [], "any", false, false, true, 31), 31, $this->source), "html", null, true);
            yield "\">
                    <svg width=\"15\" height=\"15\" viewBox=\"0 0 15 15\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                        <circle cx=\"7.5\" cy=\"7.5\" r=\"7\" stroke=\"currentColor\" stroke-dasharray=\"3 2\"/>
                        <circle cx=\"7.50001\" cy=\"7.50008\" r=\"4.66667\" transform=\"rotate(-90 7.50001 7.50008)\" fill=\"currentColor\"/>
                    </svg>
                    ";
            // line 36
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "status_label", [], "any", false, false, true, 36), 36, $this->source), "html", null, true);
            yield "
                </div>";
            // line 38
            yield "                ";
        } else {
            // line 39
            yield "                    <div class=\"note-status ";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "status_class", [], "any", false, false, true, 39), 39, $this->source), "html", null, true);
            yield "\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "status_label", [], "any", false, false, true, 39), 39, $this->source), "html", null, true);
            yield "</div>";
            // line 40
            yield "            ";
        }
        // line 41
        yield "            <h3 class=\"note-card__title\">";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "title", [], "any", false, false, true, 41), 41, $this->source), "html", null, true);
        yield "</h3>
            <p class=\"note-card__description\">";
        // line 42
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "description", [], "any", false, false, true, 42), 42, $this->source), "html", null, true);
        yield "</p>
        </div>";
        // line 44
        yield "        <div class=\"note-card__body\">
            ";
        // line 45
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "show_products", [], "any", false, false, true, 45)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 46
            yield "                <div class=\"note-section\">
                    <div class=\"note-section__title\">";
            // line 47
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "products_title", [], "any", false, false, true, 47), 47, $this->source), "html", null, true);
            yield "</div>
                    <ul class=\"note-products\">
                            ";
            // line 49
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "products", [], "any", false, false, true, 49));
            foreach ($context['_seq'] as $context["_key"] => $context["product"]) {
                // line 50
                yield "                                <li class=\"note-products__item\">";
                yield ((CoreExtension::getAttribute($this->env, $this->source, $context["product"], "name", [], "any", false, false, true, 50)) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["product"], "name", [], "any", false, false, true, 50), 50, $this->source), "html", null, true)) : ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["product"], "inv_number", [], "any", false, false, true, 50), 50, $this->source), "html", null, true)));
                yield "</li>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['product'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 52
            yield "                    </ul>
                </div>
            ";
        }
        // line 55
        yield "            ";
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "show_operations", [], "any", false, false, true, 55)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 56
            yield "                <div class=\"note-section\">
                    <div class=\"note-section__title\">";
            // line 57
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "operations_title", [], "any", false, false, true, 57), 57, $this->source), "html", null, true);
            yield "</div>
                    <div class=\"note-operations\">
                        ";
            // line 59
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "operations", [], "any", false, false, true, 59));
            foreach ($context['_seq'] as $context["_key"] => $context["op"]) {
                // line 60
                yield "                            <div class=\"note-op\">
                                ";
                // line 61
                if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, $context["op"], "can_toggle_accounting", [], "any", false, false, true, 61)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                    // line 62
                    yield "                                    <button
                                        type=\"button\"
                                        class=\"note-op__toggle\"
                                        data-request=\"onToggleAccountingStatus\"
                                        data-request-data=\"operation_id: ";
                    // line 66
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "id", [], "any", false, false, true, 66), 66, $this->source), "html", null, true);
                    yield ", note_id: ";
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "id", [], "any", false, false, true, 66), 66, $this->source), "html", null, true);
                    yield "\"
                                        data-request-flash
                                        data-request-lock>
                                        ";
                    // line 69
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "toggle_label", [], "any", false, false, true, 69), 69, $this->source), "html", null, true);
                    yield "
                                    </button>
                                ";
                }
                // line 72
                yield "                                <div class=\"note-op__title\">";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "title", [], "any", false, false, true, 72), 72, $this->source), "html", null, true);
                yield "</div>
                                ";
                // line 73
                if ((CoreExtension::getAttribute($this->env, $this->source, $context["op"], "status_label", [], "any", false, false, true, 73) == "Документи готові")) {
                    // line 74
                    yield "                                    <div class=\"note-op__status label label--extra-sm label--success\">
                                        <svg width=\"14\" height=\"14\" viewBox=\"0 0 15 15\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                                            <circle cx=\"7.5\" cy=\"7.5\" r=\"7\" stroke=\"currentColor\" stroke-dasharray=\"3 2\"/>
                                            <mask id=\"path-2-inside-1_106_1232\" fill=\"white\">
                                            <path d=\"M7.49998 2.83342C8.73766 2.83342 9.92464 3.32508 10.7998 4.20025C11.675 5.07542 12.1666 6.2624 12.1666 7.50008C12.1666 8.73776 11.675 9.92474 10.7998 10.7999C9.92464 11.6751 8.73766 12.1667 7.49998 12.1667L7.49998 7.50008V2.83342Z\"/>
                                            </mask>
                                            <path d=\"M7.49998 2.83342C8.73766 2.83342 9.92464 3.32508 10.7998 4.20025C11.675 5.07542 12.1666 6.2624 12.1666 7.50008C12.1666 8.73776 11.675 9.92474 10.7998 10.7999C9.92464 11.6751 8.73766 12.1667 7.49998 12.1667L7.49998 7.50008V2.83342Z\" fill=\"currentColor\" stroke=\"currentColor\" stroke-width=\"2\" mask=\"url(#path-2-inside-1_106_1232)\"/>
                                        </svg>
                                        ";
                    // line 82
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "status_label", [], "any", false, false, true, 82), 82, $this->source), "html", null, true);
                    yield "
                                    </div>
                                ";
                } elseif ((CoreExtension::getAttribute($this->env, $this->source,                 // line 84
$context["op"], "status_label", [], "any", false, false, true, 84) == "В бухгалтерії")) {
                    // line 85
                    yield "                                    <div class=\"note-op__status label label--extra-sm label--error\">
                                        <svg width=\"14\" height=\"14\" viewBox=\"0 0 15 15\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                                            <circle cx=\"7.5\" cy=\"7.5\" r=\"7\" stroke=\"currentColor\" stroke-dasharray=\"3 2\"/>
                                            <path d=\"M7.49998 2.83342C8.42296 2.83342 9.32521 3.10711 10.0926 3.61989C10.8601 4.13267 11.4582 4.8615 11.8114 5.71423C12.1646 6.56695 12.257 7.50526 12.077 8.4105C11.8969 9.31575 11.4525 10.1473 10.7998 10.7999C10.1472 11.4526 9.31565 11.897 8.4104 12.0771C7.50516 12.2571 6.56685 12.1647 5.71412 11.8115C4.8614 11.4583 4.13257 10.8602 3.61979 10.0927C3.10701 9.32531 2.83331 8.42306 2.83331 7.50008H7.49998V2.83342Z\" fill=\"currentColor\"/>
                                        </svg>
                                        ";
                    // line 90
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "status_label", [], "any", false, false, true, 90), 90, $this->source), "html", null, true);
                    yield "
                                    </div>
                                ";
                } elseif ((CoreExtension::getAttribute($this->env, $this->source,                 // line 92
$context["op"], "status_label", [], "any", false, false, true, 92) == "Виконано")) {
                    // line 93
                    yield "                                    <div class=\"note-op__status label label--extra-sm label--info\">
                                        <svg width=\"14\" height=\"14\" viewBox=\"0 0 15 15\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                                            <circle cx=\"7.5\" cy=\"7.5\" r=\"7\" stroke=\"currentColor\" stroke-dasharray=\"3 2\"/>
                                            <circle cx=\"7.50001\" cy=\"7.50008\" r=\"4.66667\" transform=\"rotate(-90 7.50001 7.50008)\" fill=\"currentColor\"/>
                                        </svg>
                                        ";
                    // line 98
                    yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["op"], "status_label", [], "any", false, false, true, 98), 98, $this->source), "html", null, true);
                    yield "
                                    </div>
                                ";
                }
                // line 101
                yield "                            </div>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['op'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 103
            yield "                    </div>
                </div>
            ";
        }
        // line 106
        yield "        </div>";
        // line 107
        yield "    </div>
    <div class=\"note-card__actions\">
        ";
        // line 109
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "is_single", [], "any", false, false, true, 109)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 110
            yield "            <a href=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "create_operation_url", [], "any", false, false, true, 110), 110, $this->source), "html", null, true);
            yield "\" class=\"button button--nm button--brand\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "create_operation_label", [], "any", false, false, true, 110), 110, $this->source), "html", null, true);
            yield "</a>
        ";
        } else {
            // line 112
            yield "            <a href=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "action_url", [], "any", false, false, true, 112), 112, $this->source), "html", null, true);
            yield "\" class=\"button button--nm button--brand\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["note"] ?? null), "action_label", [], "any", false, false, true, 112), 112, $this->source), "html", null, true);
            yield "</a>
        ";
        }
        // line 114
        yield "    </div>
</article>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\inventpro\\themes\\invent-pro\\partials\\notes\\item.htm";
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
        return array (  299 => 114,  291 => 112,  283 => 110,  281 => 109,  277 => 107,  275 => 106,  270 => 103,  263 => 101,  257 => 98,  250 => 93,  248 => 92,  243 => 90,  236 => 85,  234 => 84,  229 => 82,  219 => 74,  217 => 73,  212 => 72,  206 => 69,  198 => 66,  192 => 62,  190 => 61,  187 => 60,  183 => 59,  178 => 57,  175 => 56,  172 => 55,  167 => 52,  158 => 50,  154 => 49,  149 => 47,  146 => 46,  144 => 45,  141 => 44,  137 => 42,  132 => 41,  129 => 40,  123 => 39,  120 => 38,  116 => 36,  107 => 31,  104 => 30,  100 => 28,  91 => 23,  88 => 22,  84 => 20,  72 => 12,  69 => 11,  65 => 9,  57 => 5,  55 => 4,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<article class=\"note-card\" id=\"note-card-{{ note.id }}\" data-status-key=\"{{ note.filter_status_key }}\" data-created-at=\"{{ note.created_at_ts }}\">
    <div class=\"note-card__top\">
        <div class=\"note-card__header\">
            {% if note.status_label == 'В розробці' %}
                <div class=\"note-status {{ note.status_class }}\">
                    <svg width=\"15\" height=\"15\" viewBox=\"0 0 15 15\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                        <circle cx=\"7.5\" cy=\"7.5\" r=\"7\" stroke=\"currentColor\" stroke-dasharray=\"3 2\"/>
                    </svg>
                    {{ note.status_label }}
                </div>{# note-status #}
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
                </div>{# note-status #}
            {% elseif note.status_label == 'В бухгалтерії' %}
                <div class=\"note-status {{ note.status_class }}\">
                    <svg width=\"15\" height=\"15\" viewBox=\"0 0 15 15\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                        <circle cx=\"7.5\" cy=\"7.5\" r=\"7\" stroke=\"currentColor\" stroke-dasharray=\"3 2\"/>
                        <path d=\"M7.49998 2.83342C8.42296 2.83342 9.32521 3.10711 10.0926 3.61989C10.8601 4.13267 11.4582 4.8615 11.8114 5.71423C12.1646 6.56695 12.257 7.50526 12.077 8.4105C11.8969 9.31575 11.4525 10.1473 10.7998 10.7999C10.1472 11.4526 9.31565 11.897 8.4104 12.0771C7.50516 12.2571 6.56685 12.1647 5.71412 11.8115C4.8614 11.4583 4.13257 10.8602 3.61979 10.0927C3.10701 9.32531 2.83331 8.42306 2.83331 7.50008H7.49998V2.83342Z\" fill=\"currentColor\"/>
                    </svg>
                    {{ note.status_label }}
                </div>{# note-status #}
            {% elseif note.status_label == 'Виконано' %}
                <div class=\"note-status {{ note.status_class }}\">
                    <svg width=\"15\" height=\"15\" viewBox=\"0 0 15 15\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                        <circle cx=\"7.5\" cy=\"7.5\" r=\"7\" stroke=\"currentColor\" stroke-dasharray=\"3 2\"/>
                        <circle cx=\"7.50001\" cy=\"7.50008\" r=\"4.66667\" transform=\"rotate(-90 7.50001 7.50008)\" fill=\"currentColor\"/>
                    </svg>
                    {{ note.status_label }}
                </div>{# note-status #}
                {% else %}
                    <div class=\"note-status {{ note.status_class }}\">{{ note.status_label }}</div>{# note-status #}
            {% endif %}
            <h3 class=\"note-card__title\">{{ note.title }}</h3>
            <p class=\"note-card__description\">{{ note.description }}</p>
        </div>{# note-card__header #}
        <div class=\"note-card__body\">
            {% if note.show_products %}
                <div class=\"note-section\">
                    <div class=\"note-section__title\">{{ note.products_title }}</div>
                    <ul class=\"note-products\">
                            {% for product in note.products %}
                                <li class=\"note-products__item\">{{ product.name ?: product.inv_number }}</li>
                            {% endfor %}
                    </ul>
                </div>
            {% endif %}
            {% if note.show_operations %}
                <div class=\"note-section\">
                    <div class=\"note-section__title\">{{ note.operations_title }}</div>
                    <div class=\"note-operations\">
                        {% for op in note.operations %}
                            <div class=\"note-op\">
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
                                {% if op.status_label == 'Документи готові' %}
                                    <div class=\"note-op__status label label--extra-sm label--success\">
                                        <svg width=\"14\" height=\"14\" viewBox=\"0 0 15 15\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                                            <circle cx=\"7.5\" cy=\"7.5\" r=\"7\" stroke=\"currentColor\" stroke-dasharray=\"3 2\"/>
                                            <mask id=\"path-2-inside-1_106_1232\" fill=\"white\">
                                            <path d=\"M7.49998 2.83342C8.73766 2.83342 9.92464 3.32508 10.7998 4.20025C11.675 5.07542 12.1666 6.2624 12.1666 7.50008C12.1666 8.73776 11.675 9.92474 10.7998 10.7999C9.92464 11.6751 8.73766 12.1667 7.49998 12.1667L7.49998 7.50008V2.83342Z\"/>
                                            </mask>
                                            <path d=\"M7.49998 2.83342C8.73766 2.83342 9.92464 3.32508 10.7998 4.20025C11.675 5.07542 12.1666 6.2624 12.1666 7.50008C12.1666 8.73776 11.675 9.92474 10.7998 10.7999C9.92464 11.6751 8.73766 12.1667 7.49998 12.1667L7.49998 7.50008V2.83342Z\" fill=\"currentColor\" stroke=\"currentColor\" stroke-width=\"2\" mask=\"url(#path-2-inside-1_106_1232)\"/>
                                        </svg>
                                        {{ op.status_label }}
                                    </div>
                                {% elseif op.status_label == 'В бухгалтерії' %}
                                    <div class=\"note-op__status label label--extra-sm label--error\">
                                        <svg width=\"14\" height=\"14\" viewBox=\"0 0 15 15\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                                            <circle cx=\"7.5\" cy=\"7.5\" r=\"7\" stroke=\"currentColor\" stroke-dasharray=\"3 2\"/>
                                            <path d=\"M7.49998 2.83342C8.42296 2.83342 9.32521 3.10711 10.0926 3.61989C10.8601 4.13267 11.4582 4.8615 11.8114 5.71423C12.1646 6.56695 12.257 7.50526 12.077 8.4105C11.8969 9.31575 11.4525 10.1473 10.7998 10.7999C10.1472 11.4526 9.31565 11.897 8.4104 12.0771C7.50516 12.2571 6.56685 12.1647 5.71412 11.8115C4.8614 11.4583 4.13257 10.8602 3.61979 10.0927C3.10701 9.32531 2.83331 8.42306 2.83331 7.50008H7.49998V2.83342Z\" fill=\"currentColor\"/>
                                        </svg>
                                        {{ op.status_label }}
                                    </div>
                                {% elseif op.status_label == 'Виконано' %}
                                    <div class=\"note-op__status label label--extra-sm label--info\">
                                        <svg width=\"14\" height=\"14\" viewBox=\"0 0 15 15\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
                                            <circle cx=\"7.5\" cy=\"7.5\" r=\"7\" stroke=\"currentColor\" stroke-dasharray=\"3 2\"/>
                                            <circle cx=\"7.50001\" cy=\"7.50008\" r=\"4.66667\" transform=\"rotate(-90 7.50001 7.50008)\" fill=\"currentColor\"/>
                                        </svg>
                                        {{ op.status_label }}
                                    </div>
                                {% endif %}
                            </div>
                        {% endfor %}
                    </div>
                </div>
            {% endif %}
        </div>{# note-card__body #}
    </div>
    <div class=\"note-card__actions\">
        {% if note.is_single %}
            <a href=\"{{ note.create_operation_url }}\" class=\"button button--nm button--brand\">{{ note.create_operation_label }}</a>
        {% else %}
            <a href=\"{{ note.action_url }}\" class=\"button button--nm button--brand\">{{ note.action_label }}</a>
        {% endif %}
    </div>
</article>", "C:\\OSPanel\\domains\\inventpro\\themes\\invent-pro\\partials\\notes\\item.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["if" => 4, "for" => 49];
        static $filters = ["escape" => 1];
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
