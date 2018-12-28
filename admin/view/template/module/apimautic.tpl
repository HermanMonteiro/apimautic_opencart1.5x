<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div><!-- breadcrumb -->

    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>

    <div class="box">
        <div class="heading">
            <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
            <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
        </div><!-- end of .heading -->

        <div class = "content">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                <table class = "form">
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_public_key; ?></td>
                        <td><input type="text" name="public_key" value="<?php echo $public_key ?>" size="50%" />
                            <?php if ($error_content) { ?>
                            <span class="error"><?php echo $error_content; ?></span>
                            <?php } ?></td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_secret_key; ?></td>
                        <td><input type="text" name="secret_key" value="<?php echo $secret_key; ?>" size="50%" />
                            <?php if ($error_content) { ?>
                            <span class="error"><?php echo $error_content; ?></span>
                            <?php } ?></td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_base_url; ?></td>
                        <td><input type="text" name="base_url" value="<?php echo $base_url ?>" size="50%" />
                            <?php if ($error_content) { ?>
                            <span class="error"><?php echo $error_content; ?></span>
                            <?php } ?></td>
                    </tr>
                    <tr>
                        <td><span class="required">*</span> <?php echo $entry_website; ?></td>
                        <td><input type="text" name="callback" value="<?php echo $callback ?>" size="50%" />
                            <?php if ($error_content) { ?>
                            <span class="error"><?php echo $error_content; ?></span>
                            <?php } ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_status; ?><br /></td>
                        <td>
                            <select name="status" id="input-status" class="form-control">
                                <?php if ($status) { ?>
                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                <option value="0"><?php echo $text_disabled; ?></option>
                                <?php } else { ?>
                                <option value="1"><?php echo $text_enabled; ?></option>
                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <?php if ($error_mautic) { ?>
                    <tr><td><span class="error"><?php echo $error_mautic; ?></span></td></tr>
                            <?php } ?>
                    <?php if ($success_mautic) { ?>
                    <tr><td><span class="success"><?php echo $success_mautic; ?></span></td></tr>
                    <?php } ?>
                </table>
            </form>
        </div> <!-- end of .content -->