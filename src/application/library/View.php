<?php
/*/**
 * 视图引擎定义
 * @version $Id$
 */
use Jenssegers\Blade\Blade;
use Illuminate\Translation\Translator;
use Illuminate\Translation\FileLoader;
use Illuminate\Filesystem\Filesystem;
// 视图引擎实现类
class View implements Yaf_View_Interface
{
    /**
     * template object
     * @var template
     */
    public $_T;
    private $header = null;
    private $tail = null;
    private $assignData = null;
    private $vp = null;
    private $cp = null;
    /**
     * Constructor
     *
     * @param   string      $tmplPath
     * @param   array       $extraParams
     * @return  void
     */
    public function __construct($viewPath = null, $cachePath= null)
    {
        $this->_T = new Blade($viewPath,$cachePath);

        $this->_T->addExtension('html', 'php');

        $this->_T->compiler()->directive('trans', function ($expression) {
            return "<?php echo Gear::trans".$expression."; ?>";
        });

        $this->vp = $viewPath;
        $this->cp = $cachePath;

    }

    //一般用来载入css等头部
    public  function setHeader($headerPath){
        $this->header=$headerPath;
    }

    //一般用来载入js等尾部
    public  function setTail($tailPath){
        $this->tail=$tailPath;
    }
    /**
     * Assign variables to the template
     *
     * Allows setting a specific key to the specified value, OR passing
     * an array of key => value pairs to set en masse.
     *
     * @param   string|array    $spec   The assignment strategy to use
     * @param   mixed           $value  (Optional)
     * @return  void
     */
    public function assign($name, $value = NULL)
    {
        if(is_array($name)){
            $this->assignData = array_merge($this->assignData, $name);
        }else{
            $this->assignData[$name] = $value;
        }

        return $this->assignData;
    }


    public function render($view_file, $tpl_vars = null)
    {

    }

    /**
     * 获取模板路径
     * @param  [type] $view_file [description]
     * @return [type]            [description]
     */
    public function getViewPath($view_file){
        $view_paths = explode('.',$view_file);
        $real_path = null;

        foreach ($view_paths as $value) {
            $real_path .= '/'.$value;
        }

        return $this->vp.$real_path.'.blade.php';
    }

    /**
     * Processes a template and display the output.
     *
     * @param   string          $view_file
     * @param   array           $tpl_vars  (Optional)
     * @return  string
     */
    public function display($view_file, $tpl_vars = null)
    {
    }

    /**
     * setting the path of template.
     *
     * @param   string          $view_directory
     * @return  boolean
     */
    public function setScriptPath($view_directory)
    {

    }

    /**
     * return the path of template.
     *
     * @param   void
     * @return  string
     */
    public function getScriptPath()
    {
        return '';
    }


    public function make($path, $data)
    {
        if(!empty($this->assignData)){
            $data = array_merge($this->assignData, $data);
        }

        if($this->header) echo $this->_T->make($this->header,$data);

        if($this->_T->exists($path)) echo $this->_T->make($path,$data);

        if($this->tail) echo $this->_T->make($this->tail,$data);

    }

    

}

