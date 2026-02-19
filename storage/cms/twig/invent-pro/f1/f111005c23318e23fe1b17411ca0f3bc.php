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

/* C:\OSPanel\domains\inventpro-test\plugins/winter/user/components/account/register.htm */
class __TwigTemplate_28a9f0144f90a4e418848ffe8975cb35 extends Template
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
        if ((($tmp = ($context["canRegister"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 2
            yield "    <h3>";
            yield Lang::get("winter.user::frontend.general.register");
            yield "</h3>

    ";
            // line 4
            yield $this->env->getFunction('form_*')->getCallable()("ajax", "onRegister");
            yield "

        <div class=\"form-group\">
            <label for=\"registerName\">";
            // line 7
            yield Lang::get("winter.user::frontend.general.full_name");
            yield "</label>
            <input
                name=\"name\"
                type=\"text\"
                class=\"form-control\"
                id=\"registerName\"
                placeholder=\"";
            // line 13
            yield Lang::get("winter.user::frontend.general.full_name_placeholder");
            yield "\"/>
        </div>

        <div class=\"form-group\">
            <label for=\"registerEmail\">";
            // line 17
            yield Lang::get("winter.user::frontend.general.email");
            yield "</label>
            <input
                name=\"email\"
                type=\"email\"
                class=\"form-control\"
                id=\"registerEmail\"
                placeholder=\"";
            // line 23
            yield Lang::get("winter.user::frontend.general.email_placeholder");
            yield "\"/>
        </div>

        ";
            // line 26
            if ((($context["loginAttribute"] ?? null) == "username")) {
                // line 27
                yield "            <div class=\"form-group\">
                <label for=\"registerUsername\">";
                // line 28
                yield Lang::get("winter.user::frontend.general.username");
                yield "</label>
                <input
                    name=\"username\"
                    type=\"text\"
                    class=\"form-control\"
                    id=\"registerUsername\"
                    placeholder=\"";
                // line 34
                yield Lang::get("winter.user::frontend.general.username_placeholder");
                yield "\"/>
            </div>
        ";
            }
            // line 37
            yield "
        <div class=\"form-group\">
            <label for=\"registerPassword\">";
            // line 39
            yield Lang::get("winter.user::frontend.general.password");
            yield "</label>
            <input
                name=\"password\"
                type=\"password\"
                class=\"form-control\"
                id=\"registerPassword\"
                placeholder=\"";
            // line 45
            yield Lang::get("winter.user::frontend.general.password_placeholder");
            yield "\"
                autocomplete=\"new-password\"/>
        </div>

        <button type=\"submit\" class=\"btn btn-default\">";
            // line 49
            yield Lang::get("winter.user::frontend.general.register");
            yield "</button>

    ";
            // line 51
            yield $this->env->getFunction('form_*')->getCallable()("close");
            yield "
";
        } else {
            // line 53
            yield "    <!-- Registration is disabled. -->
";
        }
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\inventpro-test\\plugins/winter/user/components/account/register.htm";
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
        return array (  139 => 53,  134 => 51,  129 => 49,  122 => 45,  113 => 39,  109 => 37,  103 => 34,  94 => 28,  91 => 27,  89 => 26,  83 => 23,  74 => 17,  67 => 13,  58 => 7,  52 => 4,  46 => 2,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% if canRegister %}
    <h3>{{ 'winter.user::frontend.general.register' | trans }}</h3>

    {{ form_ajax('onRegister') }}

        <div class=\"form-group\">
            <label for=\"registerName\">{{ 'winter.user::frontend.general.full_name' | trans }}</label>
            <input
                name=\"name\"
                type=\"text\"
                class=\"form-control\"
                id=\"registerName\"
                placeholder=\"{{ 'winter.user::frontend.general.full_name_placeholder' | trans }}\"/>
        </div>

        <div class=\"form-group\">
            <label for=\"registerEmail\">{{ 'winter.user::frontend.general.email' | trans }}</label>
            <input
                name=\"email\"
                type=\"email\"
                class=\"form-control\"
                id=\"registerEmail\"
                placeholder=\"{{ 'winter.user::frontend.general.email_placeholder' | trans }}\"/>
        </div>

        {% if loginAttribute == \"username\" %}
            <div class=\"form-group\">
                <label for=\"registerUsername\">{{ 'winter.user::frontend.general.username' | trans }}</label>
                <input
                    name=\"username\"
                    type=\"text\"
                    class=\"form-control\"
                    id=\"registerUsername\"
                    placeholder=\"{{ 'winter.user::frontend.general.username_placeholder' | trans }}\"/>
            </div>
        {% endif %}

        <div class=\"form-group\">
            <label for=\"registerPassword\">{{ 'winter.user::frontend.general.password' | trans }}</label>
            <input
                name=\"password\"
                type=\"password\"
                class=\"form-control\"
                id=\"registerPassword\"
                placeholder=\"{{ 'winter.user::frontend.general.password_placeholder' | trans }}\"
                autocomplete=\"new-password\"/>
        </div>

        <button type=\"submit\" class=\"btn btn-default\">{{ 'winter.user::frontend.general.register' | trans }}</button>

    {{ form_close() }}
{% else %}
    <!-- Registration is disabled. -->
{% endif %}
", "C:\\OSPanel\\domains\\inventpro-test\\plugins/winter/user/components/account/register.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["if" => 1];
        static $filters = ["trans" => 2];
        static $functions = ["form_ajax" => 4, "form_close" => 51];

        try {
            $this->sandbox->checkSecurity(
                ['if'],
                ['trans'],
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
