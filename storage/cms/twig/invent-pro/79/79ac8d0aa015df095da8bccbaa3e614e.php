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

/* C:\OSPanel\domains\inventpro\plugins/winter/user/components/account/activation_check.htm */
class __TwigTemplate_d7dda10b551081e8e5331a7dc2138ab2 extends Template
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
        if ((($tmp =  !CoreExtension::getAttribute($this->env, $this->source, ($context["user"] ?? null), "is_activated", [], "any", false, false, true, 1)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 2
            yield "
    <h3>";
            // line 3
            yield Lang::get("winter.user::frontend.email_not_verified.title");
            yield "</h3>
    <p>
        ";
            // line 5
            yield Lang::get("winter.user::frontend.email_not_verified.description");
            yield "
        <a href=\"javascript:;\" data-request=\"onSendActivationEmail\">";
            // line 6
            yield Lang::get("winter.user::frontend.resend_verification_email");
            yield "</a>.
    </p>

";
        }
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\inventpro\\plugins/winter/user/components/account/activation_check.htm";
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
        return array (  58 => 6,  54 => 5,  49 => 3,  46 => 2,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("{% if not user.is_activated %}

    <h3>{{ 'winter.user::frontend.email_not_verified.title' | trans }}</h3>
    <p>
        {{ 'winter.user::frontend.email_not_verified.description' | trans }}
        <a href=\"javascript:;\" data-request=\"onSendActivationEmail\">{{ 'winter.user::frontend.resend_verification_email' | trans }}</a>.
    </p>

{% endif %}
", "C:\\OSPanel\\domains\\inventpro\\plugins/winter/user/components/account/activation_check.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["if" => 1];
        static $filters = ["trans" => 3];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['if'],
                ['trans'],
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
