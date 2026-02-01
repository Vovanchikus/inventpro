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

/* C:\OSPanel\domains\inventpro\themes\invent-pro\pages\add-note.htm */
class __TwigTemplate_c06c91cc9501ba8fd301c5a6ae1d1e4f extends Template
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
        yield "<section class=\"page-section page-section--centered\">
  <div class=\"container\">
    <h1>Создать заметку</h1>

    <form id=\"createNoteForm\" data-request=\"onCreateNote\" data-request-success=\"handleNoteCreated\">
      <div class=\"form-row\">
        <label>Заголовок</label>
        <input type=\"text\" name=\"title\" />
      </div>
      <div class=\"form-row\">
        <label>Описание</label>
        <textarea name=\"description\"></textarea>
      </div>
      <div class=\"form-row\">
        <label>Срок</label>
        <input type=\"date\" name=\"due_date\" />
      </div>
      <div class=\"form-row\">
        <button type=\"submit\" class=\"btn\">Создать заметку</button>
      </div>
    </form>
  </div>
</section>

<script>
  document.addEventListener('DOMContentLoaded', function(){
    // Если есть товары в localStorage под ключом createNote — добавим их в форму при submit
    const params = new URLSearchParams(window.location.search);
    const noteId = params.get('note_id');

    // Если пришли через addToNote (redirect) — товары хранятся в addToNote
    const addToNoteProducts = localStorage.getItem('addToNote');
    if (noteId && addToNoteProducts) {
      // отправим автоматически на добавление
      const products = addToNoteProducts;
      // вызов AJAX обработчика компонента
      \$.request('onCreateNote', { data: { note_id: noteId, products: products }, success(res){
        localStorage.removeItem('addToNote');
        if (res.note_id) window.location.href = '/notes/' + res.note_id;
      }});
    }

    // Если на странице есть ключ createNote (создание заметки с товарами) — при сабмите включим товары
    const form = document.getElementById('createNoteForm');
    form.addEventListener('submit', function(){
      const stored = localStorage.getItem('createNote');
      if (stored) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'products';
        input.value = stored;
        form.appendChild(input);
      }
    });
  });

  function handleNoteCreated(res) {
    localStorage.removeItem('createNote');
    if (res.note_id) window.location.href = '/notes/' + res.note_id;
  }
</script>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "C:\\OSPanel\\domains\\inventpro\\themes\\invent-pro\\pages\\add-note.htm";
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
        return new Source("<section class=\"page-section page-section--centered\">
  <div class=\"container\">
    <h1>Создать заметку</h1>

    <form id=\"createNoteForm\" data-request=\"onCreateNote\" data-request-success=\"handleNoteCreated\">
      <div class=\"form-row\">
        <label>Заголовок</label>
        <input type=\"text\" name=\"title\" />
      </div>
      <div class=\"form-row\">
        <label>Описание</label>
        <textarea name=\"description\"></textarea>
      </div>
      <div class=\"form-row\">
        <label>Срок</label>
        <input type=\"date\" name=\"due_date\" />
      </div>
      <div class=\"form-row\">
        <button type=\"submit\" class=\"btn\">Создать заметку</button>
      </div>
    </form>
  </div>
</section>

<script>
  document.addEventListener('DOMContentLoaded', function(){
    // Если есть товары в localStorage под ключом createNote — добавим их в форму при submit
    const params = new URLSearchParams(window.location.search);
    const noteId = params.get('note_id');

    // Если пришли через addToNote (redirect) — товары хранятся в addToNote
    const addToNoteProducts = localStorage.getItem('addToNote');
    if (noteId && addToNoteProducts) {
      // отправим автоматически на добавление
      const products = addToNoteProducts;
      // вызов AJAX обработчика компонента
      \$.request('onCreateNote', { data: { note_id: noteId, products: products }, success(res){
        localStorage.removeItem('addToNote');
        if (res.note_id) window.location.href = '/notes/' + res.note_id;
      }});
    }

    // Если на странице есть ключ createNote (создание заметки с товарами) — при сабмите включим товары
    const form = document.getElementById('createNoteForm');
    form.addEventListener('submit', function(){
      const stored = localStorage.getItem('createNote');
      if (stored) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'products';
        input.value = stored;
        form.appendChild(input);
      }
    });
  });

  function handleNoteCreated(res) {
    localStorage.removeItem('createNote');
    if (res.note_id) window.location.href = '/notes/' + res.note_id;
  }
</script>", "C:\\OSPanel\\domains\\inventpro\\themes\\invent-pro\\pages\\add-note.htm", "");
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
