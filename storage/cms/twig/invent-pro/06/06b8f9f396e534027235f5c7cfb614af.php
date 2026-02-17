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

/* C:\OSPanel\domains\inventpro\themes\invent-pro\partials\modals\modal.htm */
class __TwigTemplate_c006f63d94044749484cf4ef4a015ac1 extends Template
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
        yield "<div id=\"modal-container\" class=\"modal-overlay\">
  <div class=\"modal-window\">
    <div class=\"modal-header\">

      <div class=\"modal-icon-box\">
        <div class=\"modal-icon\">
          <!-- Иконка будет вставляться JS -->
        </div>";
        // line 9
        yield "      </div>";
        // line 10
        yield "
      <div class=\"modal-header__text\">
        <h2 class=\"modal-title\"></h2>
        <h3 class=\"modal-subtitle\"></h3>
      </div>";
        // line 15
        yield "
      <button class=\"modal-close\">
        <svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
          <path d=\"M21.25 12C21.25 6.91421 17.0858 2.75 12 2.75C6.91421 2.75 2.75 6.91421 2.75 12C2.75 17.0858 6.91421 21.25 12 21.25C17.0858 21.25 21.25 17.0858 21.25 12ZM22.75 12C22.75 17.9142 17.9142 22.75 12 22.75C6.08579 22.75 1.25 17.9142 1.25 12C1.25 6.08579 6.08579 1.25 12 1.25C17.9142 1.25 22.75 6.08579 22.75 12Z\" fill=\"currentColor\"/>
          <path d=\"M14.2999 8.63973C14.5928 8.34691 15.0676 8.34686 15.3604 8.63973C15.6532 8.9326 15.6532 9.4074 15.3604 9.70027L9.70027 15.3604C9.4074 15.6532 8.9326 15.6532 8.63973 15.3604C8.34686 15.0676 8.34691 14.5928 8.63973 14.2999L14.2999 8.63973Z\" fill=\"currentColor\"/>
          <path d=\"M8.63973 8.63973C8.93262 8.34683 9.40738 8.34683 9.70027 8.63973L15.3604 14.2999C15.6532 14.5928 15.6533 15.0676 15.3604 15.3604C15.0676 15.6533 14.5928 15.6532 14.2999 15.3604L8.63973 9.70027C8.34683 9.40738 8.34683 8.93262 8.63973 8.63973Z\" fill=\"currentColor\"/>
          </svg>
      </button>

    </div>";
        // line 25
        yield "    <div class=\"modal-content\">
      <!-- Контент будет вставляться JS -->
    </div>";
        // line 28
        yield "  </div>";
        // line 29
        yield "</div>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\inventpro\\themes\\invent-pro\\partials\\modals\\modal.htm";
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  78 => 29,  76 => 28,  72 => 25,  61 => 15,  55 => 10,  53 => 9,  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<div id=\"modal-container\" class=\"modal-overlay\">
  <div class=\"modal-window\">
    <div class=\"modal-header\">

      <div class=\"modal-icon-box\">
        <div class=\"modal-icon\">
          <!-- Иконка будет вставляться JS -->
        </div>{# modal-icon #}
      </div>{# modal-icon-box #}

      <div class=\"modal-header__text\">
        <h2 class=\"modal-title\"></h2>
        <h3 class=\"modal-subtitle\"></h3>
      </div>{# modal-header__text #}

      <button class=\"modal-close\">
        <svg width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\">
          <path d=\"M21.25 12C21.25 6.91421 17.0858 2.75 12 2.75C6.91421 2.75 2.75 6.91421 2.75 12C2.75 17.0858 6.91421 21.25 12 21.25C17.0858 21.25 21.25 17.0858 21.25 12ZM22.75 12C22.75 17.9142 17.9142 22.75 12 22.75C6.08579 22.75 1.25 17.9142 1.25 12C1.25 6.08579 6.08579 1.25 12 1.25C17.9142 1.25 22.75 6.08579 22.75 12Z\" fill=\"currentColor\"/>
          <path d=\"M14.2999 8.63973C14.5928 8.34691 15.0676 8.34686 15.3604 8.63973C15.6532 8.9326 15.6532 9.4074 15.3604 9.70027L9.70027 15.3604C9.4074 15.6532 8.9326 15.6532 8.63973 15.3604C8.34686 15.0676 8.34691 14.5928 8.63973 14.2999L14.2999 8.63973Z\" fill=\"currentColor\"/>
          <path d=\"M8.63973 8.63973C8.93262 8.34683 9.40738 8.34683 9.70027 8.63973L15.3604 14.2999C15.6532 14.5928 15.6533 15.0676 15.3604 15.3604C15.0676 15.6533 14.5928 15.6532 14.2999 15.3604L8.63973 9.70027C8.34683 9.40738 8.34683 8.93262 8.63973 8.63973Z\" fill=\"currentColor\"/>
          </svg>
      </button>

    </div>{# modal-header #}
    <div class=\"modal-content\">
      <!-- Контент будет вставляться JS -->
    </div>{# modal-content #}
  </div>{# modal #}
</div>{# modal-container #}", "C:\\OSPanel\\domains\\inventpro\\themes\\invent-pro\\partials\\modals\\modal.htm", "");
    }
    
    public function checkSecurity()
    {
        static $tags = [];
        static $filters = [];
        static $functions = [];

        try {
            $this->sandbox->checkSecurity(
                [],
                [],
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
