<?php
/**
 * Fuel
 *
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.7
 * @author     Luke Policinski
 * @license    MIT License
 * @copyright  2015
 * @link       http://lukepolo.com
 */

class View extends \Fuel\Core\View
{
    /**
     * Captures the output that is generated when a view is included.
     * The view data will be extracted to make local variables. This method
     * is static to prevent object scope resolution.
     *
     *     $output = $this->process_file();
     *
     * @param   string  File override
     * @param   array   variables
     * @return  string
     */
    protected function process_file($file_override = false)
    {
        $clean_room = function($__file_name, array $__data)
        {
            extract($__data, EXTR_REFS);

            // Capture the view output
            ob_start();

            try
            {
                // Load the view within the current scope
                include $__file_name;
            }
            catch (\Exception $e)
            {
                // Delete the output buffer
                ob_end_clean();

                // Re-throw the exception
                throw $e;
            }

            // Get the captured output
            $html = ob_get_contents();
            
            // and close the buffer
            ob_get_clean();
            
            return \MinifyHTML::compileMinify($html);
        };
        return $clean_room($file_override ?: $this->file_name, $this->get_data());
    }
}