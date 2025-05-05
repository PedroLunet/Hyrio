<?php
/**
 * Reusable Button Component
 * 
 * Usage with children (React-like):
 * Button::start(['variant' => 'primary']);
 * // Add any content here (optional icon, text, etc.)
 * Button::end();
 * 
 * Traditional usage:
 * Button::render('My Text', 'ph-bold ph-currency-eur', ['variant' => 'primary']);
 */

class Button
{
  private static $cssIncluded = false;

  /**
   * Available button variants
   */
  private static $variants = [
    'primary',
    'secondary',
    'accent',
    'outline',
    'text'
  ];

  /**
   * Include the CSS for the button component
   */
  public static function includeCSS()
  {
    if (!self::$cssIncluded) {
      echo '<link rel="stylesheet" href="components/button/css/button.css">';
      self::$cssIncluded = true;
    }
  }

  /**
   * Render a button with optional icon
   * 
   * @param string $text The button text
   * @param string|null $iconClass The icon class (optional)
   * @param array $attributes Additional HTML attributes
   * @return void
   */
  public static function render($text, $iconClass = null, $attributes = [])
  {
    self::includeCSS();

    $variant = self::extractVariant($attributes);
    $attributesStr = self::buildAttributesString($attributes);

    echo '<button class="btn btn-' . $variant . '"' . $attributesStr . '>';

    // Only show icon if provided
    if ($iconClass) {
      echo '<i class="' . htmlspecialchars($iconClass) . '"></i>';
    }

    echo '<span>' . $text . '</span>';
    echo '</button>';
  }

  /**
   * Start a button with custom content (React-like syntax)
   * 
   * @param array $attributes Additional HTML attributes
   * @return void
   */
  public static function start($attributes = [])
  {
    self::includeCSS();

    $variant = self::extractVariant($attributes);
    $attributesStr = self::buildAttributesString($attributes);

    echo '<button class="btn btn-' . $variant . '"' . $attributesStr . '>';
    ob_start(); // Start output buffering to capture content
  }

  /**
   * End the button and render captured content
   * 
   * @return void
   */
  public static function end()
  {
    $content = ob_get_clean(); // Get the buffered content
    echo $content; // Output the captured content
    echo '</button>';
  }

  /**
   * Extract the variant from attributes and remove it
   * 
   * @param array &$attributes Reference to attributes array
   * @return string The variant to use
   */
  private static function extractVariant(&$attributes)
  {
    $variant = 'primary'; // Default variant

    if (isset($attributes['variant'])) {
      // Check if the provided variant is valid
      if (in_array($attributes['variant'], self::$variants)) {
        $variant = $attributes['variant'];
      }
      // Remove variant from attributes to avoid it being added to HTML
      unset($attributes['variant']);
    }

    return $variant;
  }

  /**
   * Build the HTML attributes string
   * 
   * @param array $attributes The attributes array
   * @return string The built attributes string
   */
  private static function buildAttributesString($attributes)
  {
    $attributesStr = '';
    foreach ($attributes as $key => $value) {
      $attributesStr .= ' ' . htmlspecialchars($key) . '="' . htmlspecialchars($value) . '"';
    }
    return $attributesStr;
  }
}

/**
 * ButtonIcon Component for use inside Button
 */
class ButtonIcon
{
  /**
   * Render an icon for a button
   * 
   * @param string $iconClass The icon class
   * @return void
   */
  public static function render($iconClass)
  {
    echo '<i class="' . htmlspecialchars($iconClass) . '"></i>';
  }
}
?>