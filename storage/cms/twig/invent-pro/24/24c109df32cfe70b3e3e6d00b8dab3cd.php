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

/* C:\OSPanel\domains\inventpro\plugins/winter/user/components/account/update.htm */
class __TwigTemplate_a212fd688d899a76ce165d7066c1946c extends Template
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
        yield Lang::get("winter.user::frontend.general.profile");
        yield "</h3>

";
        // line 3
        yield $this->env->getFunction('form_*')->getCallable()("ajax", "onUpdate", ["flash" => 1]);
        yield "

    <div class=\"form-group\">
        <label for=\"accountName\">";
        // line 6
        yield Lang::get("winter.user::frontend.general.full_name");
        yield "</label>
        <input name=\"name\" type=\"text\" class=\"form-control\" id=\"accountName\" value=\"";
        // line 7
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["user"] ?? null), "name", [], "any", false, false, true, 7), 7, $this->source), "html", null, true);
        yield "\">
    </div>

    <div class=\"form-group\">
        <label for=\"accountEmail\">";
        // line 11
        yield Lang::get("winter.user::frontend.general.email");
        yield "</label>
        <input name=\"email\" type=\"email\" class=\"form-control\" id=\"accountEmail\" value=\"";
        // line 12
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->sandbox->ensureToStringAllowed(CoreExtension::getAttribute($this->env, $this->source, ($context["user"] ?? null), "email", [], "any", false, false, true, 12), 12, $this->source), "html", null, true);
        yield "\">
    </div>

    <div class=\"form-group\">
        <label for=\"accountPassword\">";
        // line 16
        yield Lang::get("winter.user::frontend.general.password_new");
        yield "</label>
        <input name=\"password\" type=\"password\" class=\"form-control\" id=\"accountPassword\">
    </div>

    <div class=\"form-group\">
        <label for=\"accountPasswordConfirm\">";
        // line 21
        yield Lang::get("winter.user::frontend.general.password_new_confirm");
        yield "</label>
        <input name=\"password_confirmation\" type=\"password\" class=\"form-control\" id=\"accountPasswordConfirm\">
    </div>

    <div class=\"form-group\">
        <label for=\"accountAvatar\">";
        // line 26
        yield Lang::get("winter.user::frontend.general.avatar");
        yield "</label>
        <input
            name=\"avatar\"
            type=\"file\"
            class=\"form-control\"
            id=\"accountAvatar\"
            accept=\"image/jpg, image/jpeg, image/png, image/gif, image/webp\"
            capture=\"user\"
        >

        ";
        // line 36
        if ((($tmp = CoreExtension::getAttribute($this->env, $this->source, ($context["user"] ?? null), "avatar", [], "any", false, false, true, 36)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 37
            yield "            <a href=\"javascript:;\" data-request=\"onRemoveAvatar\" data-request-flash class=\"remove-avatar\">
                ";
            // line 38
            yield Lang::get("winter.user::frontend.general.avatar_remove");
            yield "
            </a>
        ";
        }
        // line 41
        yield "    </div>

    ";
        // line 43
        if ((($tmp = ($context["updateRequiresPassword"] ?? null)) && $tmp instanceof Markup ? (string) $tmp : $tmp)) {
            // line 44
            yield "        <p>";
            yield Lang::get("winter.user::frontend.password_required_update");
            yield "</p>
        <div class=\"form-group\">
            <label for=\"accountPasswordCurrent\">";
            // line 46
            yield Lang::get("winter.user::frontend.general.password_current");
            yield " <small class=\"text-danger\">* ";
            yield Lang::get("winter.user::frontend.general.required");
            yield "</small></label>
            <input name=\"password_current\" type=\"password\" class=\"form-control\" id=\"accountPasswordCurrent\">
        </div>
    ";
        }
        // line 50
        yield "
    <button type=\"submit\" class=\"btn btn-default\">";
        // line 51
        yield Lang::get("winter.user::frontend.general.save");
        yield "</button>

";
        // line 53
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
        return "C:\\OSPanel\\domains\\inventpro\\plugins/winter/user/components/account/update.htm";
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
        return array (  147 => 53,  142 => 51,  139 => 50,  130 => 46,  124 => 44,  122 => 43,  118 => 41,  112 => 38,  109 => 37,  107 => 36,  94 => 26,  86 => 21,  78 => 16,  71 => 12,  67 => 11,  60 => 7,  56 => 6,  50 => 3,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<h3>{{ 'winter.user::frontend.general.profile' | trans }}</h3>

{{ form_ajax('onUpdate', { flash: 1 }) }}

    <div class=\"form-group\">
        <label for=\"accountName\">{{ 'winter.user::frontend.general.full_name' | trans }}</label>
        <input name=\"name\" type=\"text\" class=\"form-control\" id=\"accountName\" value=\"{{ user.name }}\">
    </div>

    <div class=\"form-group\">
        <label for=\"accountEmail\">{{ 'winter.user::frontend.general.email' | trans }}</label>
        <input name=\"email\" type=\"email\" class=\"form-control\" id=\"accountEmail\" value=\"{{ user.email }}\">
    </div>

    <div class=\"form-group\">
        <label for=\"accountPassword\">{{ 'winter.user::frontend.general.password_new' | trans }}</label>
        <input name=\"password\" type=\"password\" class=\"form-control\" id=\"accountPassword\">
    </div>

    <div class=\"form-group\">
        <label for=\"accountPasswordConfirm\">{{ 'winter.user::frontend.general.password_new_confirm' | trans }}</label>
        <input name=\"password_confirmation\" type=\"password\" class=\"form-control\" id=\"accountPasswordConfirm\">
    </div>

    <div class=\"form-group\">
        <label for=\"accountAvatar\">{{ 'winter.user::frontend.general.avatar' | trans }}</label>
        <input
            name=\"avatar\"
            type=\"file\"
            class=\"form-control\"
            id=\"accountAvatar\"
            accept=\"image/jpg, image/jpeg, image/png, image/gif, image/webp\"
            capture=\"user\"
        >

        {% if user.avatar %}
            <a href=\"javascript:;\" data-request=\"onRemoveAvatar\" data-request-flash class=\"remove-avatar\">
                {{ 'winter.user::frontend.general.avatar_remove' | trans }}
            </a>
        {% endif %}
    </div>

    {% if updateRequiresPassword %}
        <p>{{ 'winter.user::frontend.password_required_update' | trans }}</p>
        <div class=\"form-group\">
            <label for=\"accountPasswordCurrent\">{{ 'winter.user::frontend.general.password_current' | trans }} <small class=\"text-danger\">* {{ 'winter.user::frontend.general.required' | trans }}</small></label>
            <input name=\"password_current\" type=\"password\" class=\"form-control\" id=\"accountPasswordCurrent\">
        </div>
    {% endif %}

    <button type=\"submit\" class=\"btn btn-default\">{{ 'winter.user::frontend.general.save' | trans }}</button>

{{ form_close() }}
", "C:\\OSPanel\\domains\\inventpro\\plugins/winter/user/components/account/update.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = ["if" => 36];
        static $filters = ["trans" => 1, "escape" => 7];
        static $functions = ["form_ajax" => 3, "form_close" => 53];

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
