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

/* C:\OSPanel\domains\inventpro\themes\invent-pro\partials\global\category-arrow.htm */
class __TwigTemplate_863a5101567e99884da4bb5a641e3c07 extends Template
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
        yield "<svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\"
     xmlns=\"http://www.w3.org/2000/svg\">

  <defs>
    <linearGradient id=\"arrowGradient\" x1=\"0\" y1=\"0\" x2=\"1\" y2=\"1\">
      <stop offset=\"0%\" stop-color=\"#f2f2f2\"/>
      <stop offset=\"100%\" stop-color=\"#ccc\"/>
    </linearGradient>
  </defs>

  <path fill-rule=\"evenodd\" clip-rule=\"evenodd\"
        d=\"M0 11.75C0 5.26065 5.26065 0 11.75 0C18.2393 0 23.5 5.26065 23.5 11.75C23.5 18.2393 18.2393 23.5 11.75 23.5C5.26065 23.5 0 18.2393 0 11.75ZM10.2197 8.28033C9.92678 7.98744 9.92678 7.51256 10.2197 7.21967C10.5126 6.92678 10.9874 6.92678 11.2803 7.21967L15.2803 11.2197C15.421 11.3603 15.5 11.5511 15.5 11.75C15.5 11.9489 15.421 12.1397 15.2803 12.2803L11.2803 16.2803C10.9874 16.5732 10.5126 16.5732 10.2197 16.2803C9.92678 15.9874 9.92678 15.5126 10.2197 15.2197L13.6893 11.75L10.2197 8.28033Z\"
        fill=\"url(#arrowGradient)\"/>
</svg>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\inventpro\\themes\\invent-pro\\partials\\global\\category-arrow.htm";
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  44 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("<svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\"
     xmlns=\"http://www.w3.org/2000/svg\">

  <defs>
    <linearGradient id=\"arrowGradient\" x1=\"0\" y1=\"0\" x2=\"1\" y2=\"1\">
      <stop offset=\"0%\" stop-color=\"#f2f2f2\"/>
      <stop offset=\"100%\" stop-color=\"#ccc\"/>
    </linearGradient>
  </defs>

  <path fill-rule=\"evenodd\" clip-rule=\"evenodd\"
        d=\"M0 11.75C0 5.26065 5.26065 0 11.75 0C18.2393 0 23.5 5.26065 23.5 11.75C23.5 18.2393 18.2393 23.5 11.75 23.5C5.26065 23.5 0 18.2393 0 11.75ZM10.2197 8.28033C9.92678 7.98744 9.92678 7.51256 10.2197 7.21967C10.5126 6.92678 10.9874 6.92678 11.2803 7.21967L15.2803 11.2197C15.421 11.3603 15.5 11.5511 15.5 11.75C15.5 11.9489 15.421 12.1397 15.2803 12.2803L11.2803 16.2803C10.9874 16.5732 10.5126 16.5732 10.2197 16.2803C9.92678 15.9874 9.92678 15.5126 10.2197 15.2197L13.6893 11.75L10.2197 8.28033Z\"
        fill=\"url(#arrowGradient)\"/>
</svg>", "C:\\OSPanel\\domains\\inventpro\\themes\\invent-pro\\partials\\global\\category-arrow.htm", "");
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
