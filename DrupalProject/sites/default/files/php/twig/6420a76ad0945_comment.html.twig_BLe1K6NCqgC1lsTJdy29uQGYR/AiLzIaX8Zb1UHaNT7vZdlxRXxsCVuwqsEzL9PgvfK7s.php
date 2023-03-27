<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* themes/xara/templates/content/comment.html.twig */
class __TwigTemplate_788b1bd537fb87bb86fa78f1a9905cdf extends \Twig\Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $this->checkSecurity();
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 68
        $context["classes"] = [0 => (((        // line 69
($context["status"] ?? null) != "published")) ? (($context["status"] ?? null)) : ("")), 1 => ((twig_get_attribute($this->env, $this->source, twig_get_attribute($this->env, $this->source,         // line 70
($context["comment"] ?? null), "owner", [], "any", false, false, true, 70), "anonymous", [], "any", false, false, true, 70)) ? ("comment-by-anonymous") : ("")), 2 => (((        // line 71
($context["author_id"] ?? null) && (($context["author_id"] ?? null) == twig_get_attribute($this->env, $this->source, ($context["commented_entity"] ?? null), "getOwnerId", [], "method", false, false, true, 71)))) ? ("comment-by-author") : (""))];
        // line 74
        echo "<article";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["attributes"] ?? null), "addClass", [0 => "js-comment comment", 1 => ($context["classes"] ?? null)], "method", false, false, true, 74), 74, $this->source), "html", null, true);
        echo ">
  ";
        // line 80
        echo "  <header class=\"comment-header\">
    <div class=\"comment-user-picture\">
      ";
        // line 82
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["user_picture"] ?? null), 82, $this->source), "html", null, true);
        echo "
    </div><!-- /comment-user-picture -->
    <div class=\"comment-meta\">
      ";
        // line 85
        if (($context["title"] ?? null)) {
            // line 86
            echo "        ";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title_prefix"] ?? null), 86, $this->source), "html", null, true);
            echo "
        <h3";
            // line 87
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["title_attributes"] ?? null), "addClass", [0 => "comment-title"], "method", false, false, true, 87), 87, $this->source), "html", null, true);
            echo ">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title"] ?? null), 87, $this->source), "html", null, true);
            echo "</h3>
        ";
            // line 88
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["title_suffix"] ?? null), 88, $this->source), "html", null, true);
            echo "
      ";
        }
        // line 90
        echo "      <p>";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["author"] ?? null), 90, $this->source), "html", null, true);
        echo " ";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["created"] ?? null), 90, $this->source), "html", null, true);
        echo " <mark class=\"hidden\" data-comment-timestamp=\"";
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["new_indicator_timestamp"] ?? null), 90, $this->source), "html", null, true);
        echo "\"></mark></p>
      ";
        // line 91
        if (($context["parent"] ?? null)) {
            // line 92
            echo "        <p class=\"visually-hidden\">";
            echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["parent"] ?? null), 92, $this->source), "html", null, true);
            echo "</p>
      ";
        }
        // line 94
        echo "    </div><!-- /comment-meta -->
  </header>
  <div";
        // line 96
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(twig_get_attribute($this->env, $this->source, ($context["content_attributes"] ?? null), "addClass", [0 => "comment-body"], "method", false, false, true, 96), 96, $this->source), "html", null, true);
        echo ">
    ";
        // line 97
        echo $this->extensions['Drupal\Core\Template\TwigExtension']->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed(($context["content"] ?? null), 97, $this->source), "html", null, true);
        echo "
  </div> <!-- /.comment-body -->
</article>
";
    }

    public function getTemplateName()
    {
        return "themes/xara/templates/content/comment.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  102 => 97,  98 => 96,  94 => 94,  88 => 92,  86 => 91,  77 => 90,  72 => 88,  66 => 87,  61 => 86,  59 => 85,  53 => 82,  49 => 80,  44 => 74,  42 => 71,  41 => 70,  40 => 69,  39 => 68,);
    }

    public function getSourceContext()
    {
        return new Source("", "themes/xara/templates/content/comment.html.twig", "C:\\xampp\\htdocs\\DrupalProject\\themes\\xara\\templates\\content\\comment.html.twig");
    }
    
    public function checkSecurity()
    {
        static $tags = array("set" => 68, "if" => 85);
        static $filters = array("escape" => 74);
        static $functions = array();

        try {
            $this->sandbox->checkSecurity(
                ['set', 'if'],
                ['escape'],
                []
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
