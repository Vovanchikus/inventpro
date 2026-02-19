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

/* C:\OSPanel\domains\inventpro-test\plugins/winter/user/components/account/signin.htm */
class __TwigTemplate_f313e3e9164fee5edc8b3b180c9c69ce extends Template
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
        yield "<h3>";
        yield Lang::get("winter.user::frontend.general.signin");
        yield "</h3>

";
        // line 3
        yield $this->env->getFunction('form_*')->getCallable()("ajax", "onSignin");
        yield "
    <div class=\"form-group form-floating\">
        <label for=\"userSigninLogin\" class=\"form-label\">";
        // line 5
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(($context["loginAttributeLabel"] ?? null), 5, $this->source), "html", null, true);
        yield "</label>
        <input
            name=\"login\"
            type=\"text\"
            class=\"form-control form-input\"
            id=\"userSigninLogin\"
            placeholder
            autocomplete=\"username\"/>
    </div>

    <div class=\"form-group\">
        <label for=\"userSigninPassword\">";
        // line 16
        yield Lang::get("winter.user::frontend.general.password");
        yield "</label>
        <input
            name=\"password\"
            type=\"password\"
            class=\"form-control\"
            id=\"userSigninPassword\"
            placeholder=\"";
        // line 22
        yield Lang::get("winter.user::frontend.signin.password_placeholder");
        yield "\"
            autocomplete=\"current-password\"/>
    </div>

    ";
        // line 26
        if ((($context["rememberLoginMode"] ?? null) == "ask")) {
            // line 27
            yield "    <div class=\"form-group\">
        <div class=\"checkbox\">
        <label><input name=\"remember\" type=\"checkbox\" value=\"1\"> ";
            // line 29
            yield Lang::get("winter.user::frontend.general.remember");
            yield "</label>
        </div>
    </div>
    ";
        }
        // line 33
        yield "
    <button type=\"submit\" class=\"btn btn-default\">";
        // line 34
        yield Lang::get("winter.user::frontend.general.signin");
        yield "</button>

";
        // line 36
        yield $this->env->getFunction('form_*')->getCallable()("close");
        yield "
";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\inventpro-test\\plugins/winter/user/components/account/signin.htm";
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
        return array (  106 => 36,  101 => 34,  98 => 33,  91 => 29,  87 => 27,  85 => 26,  78 => 22,  69 => 16,  55 => 5,  50 => 3,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<h3>{{ 'winter.user::frontend.general.signin' | trans }}</h3>

{{ form_ajax('onSignin') }}
    <div class=\"form-group form-floating\">
        <label for=\"userSigninLogin\" class=\"form-label\">{{ loginAttributeLabel }}</label>
        <input
            name=\"login\"
            type=\"text\"
            class=\"form-control form-input\"
            id=\"userSigninLogin\"
            placeholder
            autocomplete=\"username\"/>
    </div>

    <div class=\"form-group\">
        <label for=\"userSigninPassword\">{{ 'winter.user::frontend.general.password' | trans }}</label>
        <input
            name=\"password\"
            type=\"password\"
            class=\"form-control\"
            id=\"userSigninPassword\"
            placeholder=\"{{ 'winter.user::frontend.signin.password_placeholder' | trans }}\"
            autocomplete=\"current-password\"/>
    </div>

    {% if rememberLoginMode == 'ask' %}
    <div class=\"form-group\">
        <div class=\"checkbox\">
        <label><input name=\"remember\" type=\"checkbox\" value=\"1\"> {{ 'winter.user::frontend.general.remember' | trans }}</label>
        </div>
    </div>
    {% endif %}

    <button type=\"submit\" class=\"btn btn-default\">{{ 'winter.user::frontend.general.signin' | trans }}</button>

{{ form_close() }}
", "C:\\OSPanel\\domains\\inventpro-test\\plugins/winter/user/components/account/signin.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["if" => 26];
        static $filters = ["trans" => 1, "escape" => 5];
        static $functions = ["form_ajax" => 3, "form_close" => 36];

        try {
            $this->sandbox->checkSecurity(
                ['if'],
                ['trans', 'escape'],
                ['form_ajax', 'form_close'],
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
