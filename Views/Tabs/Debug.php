<h3>DPT Debug</h3>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-2 col-xs-2">
            <form name="debugLogSettings" method="post" class="form-group">
                <table class="table">
                    <tr>
                        <td>Activate Debug:</td>
                        <td>
                            <input type="checkbox" name="debugLog" value="debug" <?php if(get_option("debugSettings") === 'debug'){ echo 'checked'; } ?>>
                        </td>
                    </tr>
                </table>
                <?php submit_button('Save changes', 'primary', 'debugLogSettings'); ?>
            </form>
        </div>
    </div>
</div>
