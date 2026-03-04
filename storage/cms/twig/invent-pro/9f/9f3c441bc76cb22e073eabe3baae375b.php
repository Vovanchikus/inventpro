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

/* C:\OSPanel\domains\inventpro-test\themes\invent-pro\pages\doc-generation-builder.htm */
class __TwigTemplate_9d795b129f9c7c1c803d41e9e5bdebcc extends Template
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
        if ((($context["user"] ?? null) && ($context["isAdmin"] ?? null))) {
            // line 2
            yield "<div class=\"doc-builder box--light\"
    data-operation-id=\"";
            // line 3
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(($context["builderOperationId"] ?? null), 3, $this->source), "html", null, true);
            yield "\"
    data-has-missing-settings=\"";
            // line 4
            yield (((($tmp = (((CoreExtension::getAttribute($this->env, $this->source, ($context["builderWarnings"] ?? null), "hasMissingSettings", [], "any", true, true, true, 4) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["builderWarnings"] ?? null), "hasMissingSettings", [], "any", false, false, true, 4)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["builderWarnings"] ?? null), "hasMissingSettings", [], "any", false, false, true, 4)) : (false))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) ? ("1") : ("0"));
            yield "\"
    data-missing-items=\"";
            // line 5
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(json_encode((((CoreExtension::getAttribute($this->env, $this->source, ($context["builderWarnings"] ?? null), "missingItems", [], "any", true, true, true, 5) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["builderWarnings"] ?? null), "missingItems", [], "any", false, false, true, 5)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["builderWarnings"] ?? null), "missingItems", [], "any", false, false, true, 5)) : ([]))), "html_attr");
            yield "\">
    <h2 class=\"doc-builder__title\">Формування документу</h2>

    <div class=\"doc-builder__layout\">

        <section class=\"doc-builder__settings\">
            <h3 class=\"doc-builder__section-title\">Тонкі налаштування</h3>

            <div class=\"doc-builder__field\">
                <label class=\"doc-builder__label\">Тип списання:</label>
                <div class=\"operation-form__type-box doc-builder__type-box\">
                    <label class=\"operation-form__type-box__label\">
                        <input type=\"radio\" name=\"doc_builder_writeoff_subtype\" value=\"autoparts\" checked hidden>
                        <span class=\"operation-form__type-box__name\">Автозапчастини</span>
                    </label>
                    <label class=\"operation-form__type-box__label\">
                        <input type=\"radio\" name=\"doc_builder_writeoff_subtype\" value=\"materials\" hidden>
                        <span class=\"operation-form__type-box__name\">Буд. матеріали</span>
                    </label>
                </div>
            </div>

            <div class=\"doc-builder__field\">
                <label class=\"doc-builder__label\">Оберіть документи для формування:</label>
                <div id=\"doc-builder-doc-list\" class=\"operation-form__type-box doc-builder__type-box\"";
            // line 29
            if (Twig\Extension\CoreExtension::testEmpty(($context["builderDocNames"] ?? null))) {
                yield " style=\"display:none;\"";
            }
            yield ">
                    ";
            // line 30
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable(($context["builderDocNames"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["docName"]) {
                // line 31
                yield "                        <label class=\"operation-form__type-box__label doc-builder__doc-label\">
                            <input type=\"checkbox\" class=\"doc-builder__doc-checkbox\" value=\"";
                // line 32
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed($context["docName"], 32, $this->source), "html_attr");
                yield "\" checked hidden>
                            <span class=\"operation-form__type-box__name doc-builder__doc-name\">
                                <span class=\"doc-builder__doc-indicator\"></span>
                                <span>";
                // line 35
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed($context["docName"], 35, $this->source), "html", null, true);
                yield "</span>
                            </span>
                        </label>
                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['docName'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 39
            yield "                </div>
                <div id=\"doc-builder-doc-empty\" class=\"doc-builder__docs-empty\"";
            // line 40
            if ((($tmp =  !Twig\Extension\CoreExtension::testEmpty(($context["builderDocNames"] ?? null))) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                yield " style=\"display:none;\"";
            }
            yield ">В операції немає документів для формування</div>
            </div>

        </section>


        <section class=\"doc-builder__general-settings\">
            <h3 class=\"doc-builder__section-title\">Загальні налаштування</h3>

            <div class=\"doc-builder__field\">
                <label class=\"doc-builder__label\" for=\"doc-builder-receiver\">На кого документ:</label>
                <div id=\"doc-builder-receiver-select\" class=\"custom-select doc-builder__select\" data-name=\"receiver_name\">
                    <div class=\"selected\">";
            // line 52
            yield (((((CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "receiver_name", [], "any", true, true, true, 52) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "receiver_name", [], "any", false, false, true, 52)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "receiver_name", [], "any", false, false, true, 52)) : (""))) ? ((((CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "receiver_name", [], "any", true, true, true, 52) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "receiver_name", [], "any", false, false, true, 52)))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "receiver_name", [], "any", false, false, true, 52), 52, $this->source), "html", null, true)) : (""))) : ("Оберіть зі списку"));
            yield "</div>
                    <div class=\"options dropdown\">
                        ";
            // line 54
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable((((CoreExtension::getAttribute($this->env, $this->source, ($context["builderRoleOptions"] ?? null), "receiver_name", [], "any", true, true, true, 54) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["builderRoleOptions"] ?? null), "receiver_name", [], "any", false, false, true, 54)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["builderRoleOptions"] ?? null), "receiver_name", [], "any", false, false, true, 54)) : ([])));
            foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                // line 55
                yield "                            <div class=\"option doc-builder__option\" data-value=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", true, true, true, 55) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 55)))) ? (CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 55)) : ("")), "html_attr");
                yield "\" data-label=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "label", [], "any", true, true, true, 55) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "label", [], "any", false, false, true, 55)))) ? (CoreExtension::getAttribute($this->env, $this->source, $context["item"], "label", [], "any", false, false, true, 55)) : ((((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", true, true, true, 55) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 55)))) ? (CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 55)) : ("")))), "html_attr");
                yield "\">
                                <span class=\"doc-builder__option-name\">";
                // line 56
                yield (((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "name", [], "any", true, true, true, 56) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "name", [], "any", false, false, true, 56)))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "name", [], "any", false, false, true, 56), 56, $this->source), "html", null, true)) : ((((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", true, true, true, 56) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 56)))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 56), 56, $this->source), "html", null, true)) : (""))));
                yield "</span>
                                <span class=\"doc-builder__option-position\">";
                // line 57
                yield (((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "position", [], "any", true, true, true, 57) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "position", [], "any", false, false, true, 57)))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "position", [], "any", false, false, true, 57), 57, $this->source), "html", null, true)) : (""));
                yield "</span>
                            </div>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['item'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 60
            yield "                    </div>
                </div>
                <input type=\"hidden\" id=\"doc-builder-receiver\" class=\"doc-builder__select-input\" value=\"";
            // line 62
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((((CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "receiver_name", [], "any", true, true, true, 62) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "receiver_name", [], "any", false, false, true, 62)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "receiver_name", [], "any", false, false, true, 62)) : ("")), "html_attr");
            yield "\" data-default=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((((CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "receiver_name", [], "any", true, true, true, 62) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "receiver_name", [], "any", false, false, true, 62)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "receiver_name", [], "any", false, false, true, 62)) : ("")), "html_attr");
            yield "\">
            </div>

            <h4 class=\"doc-builder__sub-title\">Комісія</h4>

            <div class=\"doc-builder__field\">
                <label class=\"doc-builder__label\" for=\"doc-builder-commission-head\">Голова комісії:</label>
                <div id=\"doc-builder-commission-head-select\" class=\"custom-select doc-builder__select\" data-name=\"commission_head\">
                    <div class=\"selected\">";
            // line 70
            yield (((((CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_head", [], "any", true, true, true, 70) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_head", [], "any", false, false, true, 70)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_head", [], "any", false, false, true, 70)) : (""))) ? ((((CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_head", [], "any", true, true, true, 70) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_head", [], "any", false, false, true, 70)))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_head", [], "any", false, false, true, 70), 70, $this->source), "html", null, true)) : (""))) : ("Оберіть зі списку"));
            yield "</div>
                    <div class=\"options dropdown\">
                        ";
            // line 72
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable((((CoreExtension::getAttribute($this->env, $this->source, ($context["builderRoleOptions"] ?? null), "commission_head", [], "any", true, true, true, 72) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["builderRoleOptions"] ?? null), "commission_head", [], "any", false, false, true, 72)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["builderRoleOptions"] ?? null), "commission_head", [], "any", false, false, true, 72)) : ([])));
            foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                // line 73
                yield "                            <div class=\"option doc-builder__option\" data-value=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", true, true, true, 73) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 73)))) ? (CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 73)) : ("")), "html_attr");
                yield "\" data-label=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "label", [], "any", true, true, true, 73) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "label", [], "any", false, false, true, 73)))) ? (CoreExtension::getAttribute($this->env, $this->source, $context["item"], "label", [], "any", false, false, true, 73)) : ((((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", true, true, true, 73) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 73)))) ? (CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 73)) : ("")))), "html_attr");
                yield "\">
                                <span class=\"doc-builder__option-name\">";
                // line 74
                yield (((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "name", [], "any", true, true, true, 74) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "name", [], "any", false, false, true, 74)))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "name", [], "any", false, false, true, 74), 74, $this->source), "html", null, true)) : ((((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", true, true, true, 74) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 74)))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 74), 74, $this->source), "html", null, true)) : (""))));
                yield "</span>
                                <span class=\"doc-builder__option-position\">";
                // line 75
                yield (((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "position", [], "any", true, true, true, 75) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "position", [], "any", false, false, true, 75)))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "position", [], "any", false, false, true, 75), 75, $this->source), "html", null, true)) : (""));
                yield "</span>
                            </div>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['item'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 78
            yield "                    </div>
                </div>
                <input type=\"hidden\" id=\"doc-builder-commission-head\" class=\"doc-builder__select-input\" value=\"";
            // line 80
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((((CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_head", [], "any", true, true, true, 80) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_head", [], "any", false, false, true, 80)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_head", [], "any", false, false, true, 80)) : ("")), "html_attr");
            yield "\" data-default=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((((CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_head", [], "any", true, true, true, 80) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_head", [], "any", false, false, true, 80)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_head", [], "any", false, false, true, 80)) : ("")), "html_attr");
            yield "\">
            </div>

            <div class=\"doc-builder__commission-box\">

              <div class=\"doc-builder__field\">
                  <label class=\"doc-builder__label\" for=\"doc-builder-commission-member-1\">Член комісії № 1:</label>
                  <div id=\"doc-builder-commission-member-1-select\" class=\"custom-select doc-builder__select\" data-name=\"commission_member_1\">
                      <div class=\"selected\">";
            // line 88
            yield (((((CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_1", [], "any", true, true, true, 88) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_1", [], "any", false, false, true, 88)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_1", [], "any", false, false, true, 88)) : (""))) ? ((((CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_1", [], "any", true, true, true, 88) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_1", [], "any", false, false, true, 88)))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_1", [], "any", false, false, true, 88), 88, $this->source), "html", null, true)) : (""))) : ("Оберіть зі списку"));
            yield "</div>
                      <div class=\"options dropdown\">
                          ";
            // line 90
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable((((CoreExtension::getAttribute($this->env, $this->source, ($context["builderRoleOptions"] ?? null), "commission_member_1", [], "any", true, true, true, 90) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["builderRoleOptions"] ?? null), "commission_member_1", [], "any", false, false, true, 90)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["builderRoleOptions"] ?? null), "commission_member_1", [], "any", false, false, true, 90)) : ([])));
            foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                // line 91
                yield "                              <div class=\"option doc-builder__option\" data-value=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", true, true, true, 91) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 91)))) ? (CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 91)) : ("")), "html_attr");
                yield "\" data-label=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "label", [], "any", true, true, true, 91) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "label", [], "any", false, false, true, 91)))) ? (CoreExtension::getAttribute($this->env, $this->source, $context["item"], "label", [], "any", false, false, true, 91)) : ((((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", true, true, true, 91) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 91)))) ? (CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 91)) : ("")))), "html_attr");
                yield "\">
                                  <span class=\"doc-builder__option-name\">";
                // line 92
                yield (((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "name", [], "any", true, true, true, 92) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "name", [], "any", false, false, true, 92)))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "name", [], "any", false, false, true, 92), 92, $this->source), "html", null, true)) : ((((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", true, true, true, 92) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 92)))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 92), 92, $this->source), "html", null, true)) : (""))));
                yield "</span>
                                  <span class=\"doc-builder__option-position\">";
                // line 93
                yield (((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "position", [], "any", true, true, true, 93) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "position", [], "any", false, false, true, 93)))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "position", [], "any", false, false, true, 93), 93, $this->source), "html", null, true)) : (""));
                yield "</span>
                              </div>
                          ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['item'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 96
            yield "                      </div>
                  </div>
                  <input type=\"hidden\" id=\"doc-builder-commission-member-1\" class=\"doc-builder__select-input\" value=\"";
            // line 98
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((((CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_1", [], "any", true, true, true, 98) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_1", [], "any", false, false, true, 98)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_1", [], "any", false, false, true, 98)) : ("")), "html_attr");
            yield "\" data-default=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((((CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_1", [], "any", true, true, true, 98) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_1", [], "any", false, false, true, 98)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_1", [], "any", false, false, true, 98)) : ("")), "html_attr");
            yield "\">
              </div>

              <div class=\"doc-builder__field\">
                  <label class=\"doc-builder__label\" for=\"doc-builder-commission-member-2\">Член комісії № 2:</label>
                  <div id=\"doc-builder-commission-member-2-select\" class=\"custom-select doc-builder__select\" data-name=\"commission_member_2\">
                      <div class=\"selected\">";
            // line 104
            yield (((((CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_2", [], "any", true, true, true, 104) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_2", [], "any", false, false, true, 104)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_2", [], "any", false, false, true, 104)) : (""))) ? ((((CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_2", [], "any", true, true, true, 104) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_2", [], "any", false, false, true, 104)))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_2", [], "any", false, false, true, 104), 104, $this->source), "html", null, true)) : (""))) : ("Оберіть зі списку"));
            yield "</div>
                      <div class=\"options dropdown\">
                          ";
            // line 106
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable((((CoreExtension::getAttribute($this->env, $this->source, ($context["builderRoleOptions"] ?? null), "commission_member_2", [], "any", true, true, true, 106) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["builderRoleOptions"] ?? null), "commission_member_2", [], "any", false, false, true, 106)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["builderRoleOptions"] ?? null), "commission_member_2", [], "any", false, false, true, 106)) : ([])));
            foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                // line 107
                yield "                              <div class=\"option doc-builder__option\" data-value=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", true, true, true, 107) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 107)))) ? (CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 107)) : ("")), "html_attr");
                yield "\" data-label=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "label", [], "any", true, true, true, 107) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "label", [], "any", false, false, true, 107)))) ? (CoreExtension::getAttribute($this->env, $this->source, $context["item"], "label", [], "any", false, false, true, 107)) : ((((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", true, true, true, 107) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 107)))) ? (CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 107)) : ("")))), "html_attr");
                yield "\">
                                  <span class=\"doc-builder__option-name\">";
                // line 108
                yield (((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "name", [], "any", true, true, true, 108) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "name", [], "any", false, false, true, 108)))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "name", [], "any", false, false, true, 108), 108, $this->source), "html", null, true)) : ((((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", true, true, true, 108) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 108)))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 108), 108, $this->source), "html", null, true)) : (""))));
                yield "</span>
                                  <span class=\"doc-builder__option-position\">";
                // line 109
                yield (((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "position", [], "any", true, true, true, 109) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "position", [], "any", false, false, true, 109)))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "position", [], "any", false, false, true, 109), 109, $this->source), "html", null, true)) : (""));
                yield "</span>
                              </div>
                          ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['item'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 112
            yield "                      </div>
                  </div>
                  <input type=\"hidden\" id=\"doc-builder-commission-member-2\" class=\"doc-builder__select-input\" value=\"";
            // line 114
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((((CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_2", [], "any", true, true, true, 114) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_2", [], "any", false, false, true, 114)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_2", [], "any", false, false, true, 114)) : ("")), "html_attr");
            yield "\" data-default=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((((CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_2", [], "any", true, true, true, 114) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_2", [], "any", false, false, true, 114)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_2", [], "any", false, false, true, 114)) : ("")), "html_attr");
            yield "\">
              </div>

              <div class=\"doc-builder__field\">
                  <label class=\"doc-builder__label\" for=\"doc-builder-commission-member-3\">Член комісії № 3:</label>
                  <div id=\"doc-builder-commission-member-3-select\" class=\"custom-select doc-builder__select\" data-name=\"commission_member_3\">
                      <div class=\"selected\">";
            // line 120
            yield (((((CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_3", [], "any", true, true, true, 120) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_3", [], "any", false, false, true, 120)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_3", [], "any", false, false, true, 120)) : (""))) ? ((((CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_3", [], "any", true, true, true, 120) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_3", [], "any", false, false, true, 120)))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_3", [], "any", false, false, true, 120), 120, $this->source), "html", null, true)) : (""))) : ("Оберіть зі списку"));
            yield "</div>
                      <div class=\"options dropdown\">
                          ";
            // line 122
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable((((CoreExtension::getAttribute($this->env, $this->source, ($context["builderRoleOptions"] ?? null), "commission_member_3", [], "any", true, true, true, 122) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["builderRoleOptions"] ?? null), "commission_member_3", [], "any", false, false, true, 122)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["builderRoleOptions"] ?? null), "commission_member_3", [], "any", false, false, true, 122)) : ([])));
            foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                // line 123
                yield "                              <div class=\"option doc-builder__option\" data-value=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", true, true, true, 123) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 123)))) ? (CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 123)) : ("")), "html_attr");
                yield "\" data-label=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "label", [], "any", true, true, true, 123) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "label", [], "any", false, false, true, 123)))) ? (CoreExtension::getAttribute($this->env, $this->source, $context["item"], "label", [], "any", false, false, true, 123)) : ((((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", true, true, true, 123) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 123)))) ? (CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 123)) : ("")))), "html_attr");
                yield "\">
                                  <span class=\"doc-builder__option-name\">";
                // line 124
                yield (((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "name", [], "any", true, true, true, 124) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "name", [], "any", false, false, true, 124)))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "name", [], "any", false, false, true, 124), 124, $this->source), "html", null, true)) : ((((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", true, true, true, 124) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 124)))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 124), 124, $this->source), "html", null, true)) : (""))));
                yield "</span>
                                  <span class=\"doc-builder__option-position\">";
                // line 125
                yield (((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "position", [], "any", true, true, true, 125) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "position", [], "any", false, false, true, 125)))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "position", [], "any", false, false, true, 125), 125, $this->source), "html", null, true)) : (""));
                yield "</span>
                              </div>
                          ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['item'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 128
            yield "                      </div>
                  </div>
                  <input type=\"hidden\" id=\"doc-builder-commission-member-3\" class=\"doc-builder__select-input\" value=\"";
            // line 130
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((((CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_3", [], "any", true, true, true, 130) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_3", [], "any", false, false, true, 130)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_3", [], "any", false, false, true, 130)) : ("")), "html_attr");
            yield "\" data-default=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((((CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_3", [], "any", true, true, true, 130) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_3", [], "any", false, false, true, 130)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "commission_member_3", [], "any", false, false, true, 130)) : ("")), "html_attr");
            yield "\">
              </div>
            </div>";
            // line 133
            yield "
            <div class=\"doc-builder__field\">
                <label class=\"doc-builder__label\" for=\"doc-builder-responsible\">Матеріально-відповідальна особа:</label>
                <div id=\"doc-builder-responsible-select\" class=\"custom-select doc-builder__select\" data-name=\"responsible_person\">
                    <div class=\"selected\">";
            // line 137
            yield (((((CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "responsible_person", [], "any", true, true, true, 137) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "responsible_person", [], "any", false, false, true, 137)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "responsible_person", [], "any", false, false, true, 137)) : (""))) ? ((((CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "responsible_person", [], "any", true, true, true, 137) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "responsible_person", [], "any", false, false, true, 137)))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "responsible_person", [], "any", false, false, true, 137), 137, $this->source), "html", null, true)) : (""))) : ("Оберіть зі списку"));
            yield "</div>
                    <div class=\"options dropdown\">
                        ";
            // line 139
            $context['_parent'] = $context;
            $context['_seq'] = CoreExtension::ensureTraversable((((CoreExtension::getAttribute($this->env, $this->source, ($context["builderRoleOptions"] ?? null), "responsible_person", [], "any", true, true, true, 139) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["builderRoleOptions"] ?? null), "responsible_person", [], "any", false, false, true, 139)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["builderRoleOptions"] ?? null), "responsible_person", [], "any", false, false, true, 139)) : ([])));
            foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                // line 140
                yield "                            <div class=\"option doc-builder__option\" data-value=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", true, true, true, 140) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 140)))) ? (CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 140)) : ("")), "html_attr");
                yield "\" data-label=\"";
                yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "label", [], "any", true, true, true, 140) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "label", [], "any", false, false, true, 140)))) ? (CoreExtension::getAttribute($this->env, $this->source, $context["item"], "label", [], "any", false, false, true, 140)) : ((((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", true, true, true, 140) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 140)))) ? (CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 140)) : ("")))), "html_attr");
                yield "\">
                                <span class=\"doc-builder__option-name\">";
                // line 141
                yield (((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "name", [], "any", true, true, true, 141) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "name", [], "any", false, false, true, 141)))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "name", [], "any", false, false, true, 141), 141, $this->source), "html", null, true)) : ((((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", true, true, true, 141) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 141)))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "value", [], "any", false, false, true, 141), 141, $this->source), "html", null, true)) : (""))));
                yield "</span>
                                <span class=\"doc-builder__option-position\">";
                // line 142
                yield (((CoreExtension::getAttribute($this->env, $this->source, $context["item"], "position", [], "any", true, true, true, 142) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, $context["item"], "position", [], "any", false, false, true, 142)))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, $context["item"], "position", [], "any", false, false, true, 142), 142, $this->source), "html", null, true)) : (""));
                yield "</span>
                            </div>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_key'], $context['item'], $context['_parent']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 145
            yield "                    </div>
                </div>
                <input type=\"hidden\" id=\"doc-builder-responsible\" class=\"doc-builder__select-input\" value=\"";
            // line 147
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((((CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "responsible_person", [], "any", true, true, true, 147) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "responsible_person", [], "any", false, false, true, 147)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "responsible_person", [], "any", false, false, true, 147)) : ("")), "html_attr");
            yield "\" data-default=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape((((CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "responsible_person", [], "any", true, true, true, 147) &&  !(null === CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "responsible_person", [], "any", false, false, true, 147)))) ? (CoreExtension::getAttribute($this->env, $this->source, ($context["builderDefaults"] ?? null), "responsible_person", [], "any", false, false, true, 147)) : ("")), "html_attr");
            yield "\">
            </div>
        </section>
    </div>

    <div class=\"doc-builder__actions\">
        <button type=\"button\" id=\"doc-builder-reset\" class=\"button button--nm button--secondary\">Відновити налаштування</button>
        <button type=\"button\" id=\"doc-builder-generate\" class=\"button button--nm button--brand\"";
            // line 154
            if ((($tmp =  !($context["builderCanGenerate"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
                yield " disabled";
            }
            yield ">Сформувати документи</button>
    </div>
</div>

";
            // line 158
            $context['__cms_partial_params'] = [];
            echo $this->env->getExtension('Cms\Twig\Extension')->partialFunction("doc-builder/modals"            , $context['__cms_partial_params']            , true            );
            unset($context['__cms_partial_params']);
        } else {
            // line 160
            yield "    <p>У вас нет прав для формування документів.</p>
";
        }
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\inventpro-test\\themes\\invent-pro\\pages\\doc-generation-builder.htm";
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
        return array (  418 => 160,  413 => 158,  404 => 154,  392 => 147,  388 => 145,  379 => 142,  375 => 141,  368 => 140,  364 => 139,  359 => 137,  353 => 133,  346 => 130,  342 => 128,  333 => 125,  329 => 124,  322 => 123,  318 => 122,  313 => 120,  302 => 114,  298 => 112,  289 => 109,  285 => 108,  278 => 107,  274 => 106,  269 => 104,  258 => 98,  254 => 96,  245 => 93,  241 => 92,  234 => 91,  230 => 90,  225 => 88,  212 => 80,  208 => 78,  199 => 75,  195 => 74,  188 => 73,  184 => 72,  179 => 70,  166 => 62,  162 => 60,  153 => 57,  149 => 56,  142 => 55,  138 => 54,  133 => 52,  116 => 40,  113 => 39,  103 => 35,  97 => 32,  94 => 31,  90 => 30,  84 => 29,  57 => 5,  53 => 4,  49 => 3,  46 => 2,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% if user and isAdmin %}
<div class=\"doc-builder box--light\"
    data-operation-id=\"{{ builderOperationId }}\"
    data-has-missing-settings=\"{{ (builderWarnings.hasMissingSettings ?? false) ? '1' : '0' }}\"
    data-missing-items=\"{{ (builderWarnings.missingItems ?? [])|json_encode|e('html_attr') }}\">
    <h2 class=\"doc-builder__title\">Формування документу</h2>

    <div class=\"doc-builder__layout\">

        <section class=\"doc-builder__settings\">
            <h3 class=\"doc-builder__section-title\">Тонкі налаштування</h3>

            <div class=\"doc-builder__field\">
                <label class=\"doc-builder__label\">Тип списання:</label>
                <div class=\"operation-form__type-box doc-builder__type-box\">
                    <label class=\"operation-form__type-box__label\">
                        <input type=\"radio\" name=\"doc_builder_writeoff_subtype\" value=\"autoparts\" checked hidden>
                        <span class=\"operation-form__type-box__name\">Автозапчастини</span>
                    </label>
                    <label class=\"operation-form__type-box__label\">
                        <input type=\"radio\" name=\"doc_builder_writeoff_subtype\" value=\"materials\" hidden>
                        <span class=\"operation-form__type-box__name\">Буд. матеріали</span>
                    </label>
                </div>
            </div>

            <div class=\"doc-builder__field\">
                <label class=\"doc-builder__label\">Оберіть документи для формування:</label>
                <div id=\"doc-builder-doc-list\" class=\"operation-form__type-box doc-builder__type-box\"{% if builderDocNames is empty %} style=\"display:none;\"{% endif %}>
                    {% for docName in builderDocNames %}
                        <label class=\"operation-form__type-box__label doc-builder__doc-label\">
                            <input type=\"checkbox\" class=\"doc-builder__doc-checkbox\" value=\"{{ docName|e('html_attr') }}\" checked hidden>
                            <span class=\"operation-form__type-box__name doc-builder__doc-name\">
                                <span class=\"doc-builder__doc-indicator\"></span>
                                <span>{{ docName }}</span>
                            </span>
                        </label>
                    {% endfor %}
                </div>
                <div id=\"doc-builder-doc-empty\" class=\"doc-builder__docs-empty\"{% if builderDocNames is not empty %} style=\"display:none;\"{% endif %}>В операції немає документів для формування</div>
            </div>

        </section>


        <section class=\"doc-builder__general-settings\">
            <h3 class=\"doc-builder__section-title\">Загальні налаштування</h3>

            <div class=\"doc-builder__field\">
                <label class=\"doc-builder__label\" for=\"doc-builder-receiver\">На кого документ:</label>
                <div id=\"doc-builder-receiver-select\" class=\"custom-select doc-builder__select\" data-name=\"receiver_name\">
                    <div class=\"selected\">{{ (builderDefaults.receiver_name ?? '') ?: 'Оберіть зі списку' }}</div>
                    <div class=\"options dropdown\">
                        {% for item in builderRoleOptions.receiver_name ?? [] %}
                            <div class=\"option doc-builder__option\" data-value=\"{{ (item.value ?? '')|e('html_attr') }}\" data-label=\"{{ (item.label ?? item.value ?? '')|e('html_attr') }}\">
                                <span class=\"doc-builder__option-name\">{{ item.name ?? item.value ?? '' }}</span>
                                <span class=\"doc-builder__option-position\">{{ item.position ?? '' }}</span>
                            </div>
                        {% endfor %}
                    </div>
                </div>
                <input type=\"hidden\" id=\"doc-builder-receiver\" class=\"doc-builder__select-input\" value=\"{{ (builderDefaults.receiver_name ?? '')|e('html_attr') }}\" data-default=\"{{ (builderDefaults.receiver_name ?? '')|e('html_attr') }}\">
            </div>

            <h4 class=\"doc-builder__sub-title\">Комісія</h4>

            <div class=\"doc-builder__field\">
                <label class=\"doc-builder__label\" for=\"doc-builder-commission-head\">Голова комісії:</label>
                <div id=\"doc-builder-commission-head-select\" class=\"custom-select doc-builder__select\" data-name=\"commission_head\">
                    <div class=\"selected\">{{ (builderDefaults.commission_head ?? '') ?: 'Оберіть зі списку' }}</div>
                    <div class=\"options dropdown\">
                        {% for item in builderRoleOptions.commission_head ?? [] %}
                            <div class=\"option doc-builder__option\" data-value=\"{{ (item.value ?? '')|e('html_attr') }}\" data-label=\"{{ (item.label ?? item.value ?? '')|e('html_attr') }}\">
                                <span class=\"doc-builder__option-name\">{{ item.name ?? item.value ?? '' }}</span>
                                <span class=\"doc-builder__option-position\">{{ item.position ?? '' }}</span>
                            </div>
                        {% endfor %}
                    </div>
                </div>
                <input type=\"hidden\" id=\"doc-builder-commission-head\" class=\"doc-builder__select-input\" value=\"{{ (builderDefaults.commission_head ?? '')|e('html_attr') }}\" data-default=\"{{ (builderDefaults.commission_head ?? '')|e('html_attr') }}\">
            </div>

            <div class=\"doc-builder__commission-box\">

              <div class=\"doc-builder__field\">
                  <label class=\"doc-builder__label\" for=\"doc-builder-commission-member-1\">Член комісії № 1:</label>
                  <div id=\"doc-builder-commission-member-1-select\" class=\"custom-select doc-builder__select\" data-name=\"commission_member_1\">
                      <div class=\"selected\">{{ (builderDefaults.commission_member_1 ?? '') ?: 'Оберіть зі списку' }}</div>
                      <div class=\"options dropdown\">
                          {% for item in builderRoleOptions.commission_member_1 ?? [] %}
                              <div class=\"option doc-builder__option\" data-value=\"{{ (item.value ?? '')|e('html_attr') }}\" data-label=\"{{ (item.label ?? item.value ?? '')|e('html_attr') }}\">
                                  <span class=\"doc-builder__option-name\">{{ item.name ?? item.value ?? '' }}</span>
                                  <span class=\"doc-builder__option-position\">{{ item.position ?? '' }}</span>
                              </div>
                          {% endfor %}
                      </div>
                  </div>
                  <input type=\"hidden\" id=\"doc-builder-commission-member-1\" class=\"doc-builder__select-input\" value=\"{{ (builderDefaults.commission_member_1 ?? '')|e('html_attr') }}\" data-default=\"{{ (builderDefaults.commission_member_1 ?? '')|e('html_attr') }}\">
              </div>

              <div class=\"doc-builder__field\">
                  <label class=\"doc-builder__label\" for=\"doc-builder-commission-member-2\">Член комісії № 2:</label>
                  <div id=\"doc-builder-commission-member-2-select\" class=\"custom-select doc-builder__select\" data-name=\"commission_member_2\">
                      <div class=\"selected\">{{ (builderDefaults.commission_member_2 ?? '') ?: 'Оберіть зі списку' }}</div>
                      <div class=\"options dropdown\">
                          {% for item in builderRoleOptions.commission_member_2 ?? [] %}
                              <div class=\"option doc-builder__option\" data-value=\"{{ (item.value ?? '')|e('html_attr') }}\" data-label=\"{{ (item.label ?? item.value ?? '')|e('html_attr') }}\">
                                  <span class=\"doc-builder__option-name\">{{ item.name ?? item.value ?? '' }}</span>
                                  <span class=\"doc-builder__option-position\">{{ item.position ?? '' }}</span>
                              </div>
                          {% endfor %}
                      </div>
                  </div>
                  <input type=\"hidden\" id=\"doc-builder-commission-member-2\" class=\"doc-builder__select-input\" value=\"{{ (builderDefaults.commission_member_2 ?? '')|e('html_attr') }}\" data-default=\"{{ (builderDefaults.commission_member_2 ?? '')|e('html_attr') }}\">
              </div>

              <div class=\"doc-builder__field\">
                  <label class=\"doc-builder__label\" for=\"doc-builder-commission-member-3\">Член комісії № 3:</label>
                  <div id=\"doc-builder-commission-member-3-select\" class=\"custom-select doc-builder__select\" data-name=\"commission_member_3\">
                      <div class=\"selected\">{{ (builderDefaults.commission_member_3 ?? '') ?: 'Оберіть зі списку' }}</div>
                      <div class=\"options dropdown\">
                          {% for item in builderRoleOptions.commission_member_3 ?? [] %}
                              <div class=\"option doc-builder__option\" data-value=\"{{ (item.value ?? '')|e('html_attr') }}\" data-label=\"{{ (item.label ?? item.value ?? '')|e('html_attr') }}\">
                                  <span class=\"doc-builder__option-name\">{{ item.name ?? item.value ?? '' }}</span>
                                  <span class=\"doc-builder__option-position\">{{ item.position ?? '' }}</span>
                              </div>
                          {% endfor %}
                      </div>
                  </div>
                  <input type=\"hidden\" id=\"doc-builder-commission-member-3\" class=\"doc-builder__select-input\" value=\"{{ (builderDefaults.commission_member_3 ?? '')|e('html_attr') }}\" data-default=\"{{ (builderDefaults.commission_member_3 ?? '')|e('html_attr') }}\">
              </div>
            </div>{# doc-builder__commission-box #}

            <div class=\"doc-builder__field\">
                <label class=\"doc-builder__label\" for=\"doc-builder-responsible\">Матеріально-відповідальна особа:</label>
                <div id=\"doc-builder-responsible-select\" class=\"custom-select doc-builder__select\" data-name=\"responsible_person\">
                    <div class=\"selected\">{{ (builderDefaults.responsible_person ?? '') ?: 'Оберіть зі списку' }}</div>
                    <div class=\"options dropdown\">
                        {% for item in builderRoleOptions.responsible_person ?? [] %}
                            <div class=\"option doc-builder__option\" data-value=\"{{ (item.value ?? '')|e('html_attr') }}\" data-label=\"{{ (item.label ?? item.value ?? '')|e('html_attr') }}\">
                                <span class=\"doc-builder__option-name\">{{ item.name ?? item.value ?? '' }}</span>
                                <span class=\"doc-builder__option-position\">{{ item.position ?? '' }}</span>
                            </div>
                        {% endfor %}
                    </div>
                </div>
                <input type=\"hidden\" id=\"doc-builder-responsible\" class=\"doc-builder__select-input\" value=\"{{ (builderDefaults.responsible_person ?? '')|e('html_attr') }}\" data-default=\"{{ (builderDefaults.responsible_person ?? '')|e('html_attr') }}\">
            </div>
        </section>
    </div>

    <div class=\"doc-builder__actions\">
        <button type=\"button\" id=\"doc-builder-reset\" class=\"button button--nm button--secondary\">Відновити налаштування</button>
        <button type=\"button\" id=\"doc-builder-generate\" class=\"button button--nm button--brand\"{% if not builderCanGenerate %} disabled{% endif %}>Сформувати документи</button>
    </div>
</div>

{% partial 'doc-builder/modals' %}
{% else %}
    <p>У вас нет прав для формування документів.</p>
{% endif %}", "C:\\OSPanel\\domains\\inventpro-test\\themes\\invent-pro\\pages\\doc-generation-builder.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["if" => 1, "for" => 30, "partial" => 158];
        static $filters = ["escape" => 3, "e" => 5, "json_encode" => 5];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['if', 'for', 'partial'],
                ['escape', 'e', 'json_encode'],
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
