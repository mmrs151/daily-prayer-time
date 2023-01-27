<?php 
require_once __DIR__ . '/../../Models/Processors/DebugProcessor.php';
$debug = new DPTDebugProcessor();
?>

<h3>DPT Debug</h3>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-4 col-xs-2">
            <form name="debugLogSettings" method="post" class="form-group">
                <table class="table">
                    <tr>
                        <td>Activate:(experimental)</td>
                        <td>
                            <input type="checkbox" name="debugLog" value="debug" <?php if(get_option("debugActivated") === 'debug'){ echo 'checked'; } ?>>
                        </td>
                    </tr>
                </table>
                <?php submit_button('Save changes', 'primary', 'debugLogSettings'); ?>
            </form>
            <div>
                <textarea cols="120" rows="20"> 
                    <?php 
                        $lines = file($debug->getFilePath()); 
                        foreach($lines as $i=>$line) {
                            echo $line  . "\n ";
                        }
                    ?>
                </textarea>
            </div>
        </div>
    </div>
</div>
<a href="<?PHP $debug->resetLog(); ?>">RESET LOG</a>