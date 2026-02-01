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

/* C:\OSPanel\domains\inventpro\plugins/winter/user/components/account/deactivate_link.htm */
class __TwigTemplate_3a1713981d95a9c9b4983dbc33ce6ec0 extends Template
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
        yield "<script>
function toggleAccountDeactivateForm() {
    const form = document.getElementById('accountDeactivateForm');
    form.style.display = (form.style.display === 'block') ? 'none' : 'block';
}
</script>

<a
    href=\"javascript:;\"
    onclick=\"toggleAccountDeactivateForm()\"
    class=\"deactivate\"
>
    ";
        // line 13
        yield Lang::get("winter.user::frontend.deactivate_account.title");
        yield "
</a>

<div id=\"accountDeactivateForm\" style=\"display: none\">
    ";
        // line 17
        yield $this->env->getFunction('form_*')->getCallable()("ajax", "onDeactivate");
        yield "
        <h3>";
        // line 18
        yield Lang::get("winter.user::frontend.deactivate_account.prompt");
        yield "</h3>
        <p>
            ";
        // line 20
        yield Lang::get("winter.user::frontend.deactivate_account.description");
        yield "
        </p>
        <div class=\"form-group\">
            <label for=\"accountDeletePassword\">";
        // line 23
        yield Lang::get("winter.user::frontend.continue_with_password");
        yield "</label>
            <input name=\"password\" type=\"password\" class=\"form-control\" id=\"accountDeletePassword\" />
        </div>
        <button type=\"submit\" class=\"btn btn-danger\">
            ";
        // line 27
        yield Lang::get("winter.user::frontend.deactivate_account.confirm");
        yield "
        </button>
        <a
            href=\"javascript:;\"
            onclick=\"toggleAccountDeactivateForm()\">
            ";
        // line 32
        yield Lang::get("winter.user::frontend.deactivate_account.cancel");
        yield "
        </a>
    ";
        // line 34
        yield $this->env->getFunction('form_*')->getCallable()("close");
        yield "
</div>
";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\inventpro\\plugins/winter/user/components/account/deactivate_link.htm";
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
        return array (  100 => 34,  95 => 32,  87 => 27,  80 => 23,  74 => 20,  69 => 18,  65 => 17,  58 => 13,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<script>
function toggleAccountDeactivateForm() {
    const form = document.getElementById('accountDeactivateForm');
    form.style.display = (form.style.display === 'block') ? 'none' : 'block';
}
</script>

<a
    href=\"javascript:;\"
    onclick=\"toggleAccountDeactivateForm()\"
    class=\"deactivate\"
>
    {{ 'winter.user::frontend.deactivate_account.title' | trans }}
</a>

<div id=\"accountDeactivateForm\" style=\"display: none\">
    {{ form_ajax('onDeactivate') }}
        <h3>{{ 'winter.user::frontend.deactivate_account.prompt' | trans }}</h3>
        <p>
            {{ 'winter.user::frontend.deactivate_account.description' | trans }}
        </p>
        <div class=\"form-group\">
            <label for=\"accountDeletePassword\">{{ 'winter.user::frontend.continue_with_password' | trans }}</label>
            <input name=\"password\" type=\"password\" class=\"form-control\" id=\"accountDeletePassword\" />
        </div>
        <button type=\"submit\" class=\"btn btn-danger\">
            {{ 'winter.user::frontend.deactivate_account.confirm' | trans }}
        </button>
        <a
            href=\"javascript:;\"
            onclick=\"toggleAccountDeactivateForm()\">
            {{ 'winter.user::frontend.deactivate_account.cancel' | trans }}
        </a>
    {{ form_close() }}
</div>
", "C:\\OSPanel\\domains\\inventpro\\plugins/winter/user/components/account/deactivate_link.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = [];
        static $filters = ["trans" => 13];
        static $functions = ["form_ajax" => 17, "form_close" => 34];

        try {
            $this->sandbox->checkSecurity(
                [],
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
