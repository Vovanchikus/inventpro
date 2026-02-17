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

/* C:\OSPanel\domains\inventpro\themes\invent-pro\partials\modals\create_note.htm */
class __TwigTemplate_db5f2cc56f46b38dd2c0b4c7ddb12f71 extends Template
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
        yield "<form id=\"modalCreateNoteForm\" class=\"create-note__form\">

    <div class=\"create-note__inputs\">
        <div class=\"create-note__title form-floating\">
            <input type=\"text\" name=\"title\" class=\"form-input\" placeholder>
            <label class=\"form-label\">Назва нотатки</label>
        </div>
        <div class=\"create-note__description form-floating\">
            <textarea name=\"description\" class=\"form-input\" placeholder=\"Опис нотатки\"></textarea>
        </div>
        <div class=\"create-note__due-date form-floating\">
            <input type=\"date\" name=\"due_date\" class=\"form-input\" placeholder>
            <label class=\"form-label\">Дедлайн</label>
        </div>
    </div>

    <div class=\"create-note__button\">
        <button type=\"submit\" class=\"button button--brand button--nm button--ico-left\">Створити</button>
    </div>

</form>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\inventpro\\themes\\invent-pro\\partials\\modals\\create_note.htm";
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
        return new Source("<form id=\"modalCreateNoteForm\" class=\"create-note__form\">

    <div class=\"create-note__inputs\">
        <div class=\"create-note__title form-floating\">
            <input type=\"text\" name=\"title\" class=\"form-input\" placeholder>
            <label class=\"form-label\">Назва нотатки</label>
        </div>
        <div class=\"create-note__description form-floating\">
            <textarea name=\"description\" class=\"form-input\" placeholder=\"Опис нотатки\"></textarea>
        </div>
        <div class=\"create-note__due-date form-floating\">
            <input type=\"date\" name=\"due_date\" class=\"form-input\" placeholder>
            <label class=\"form-label\">Дедлайн</label>
        </div>
    </div>

    <div class=\"create-note__button\">
        <button type=\"submit\" class=\"button button--brand button--nm button--ico-left\">Створити</button>
    </div>

</form>", "C:\\OSPanel\\domains\\inventpro\\themes\\invent-pro\\partials\\modals\\create_note.htm", "");
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
