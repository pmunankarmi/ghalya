<?php
$email_args = is_array($args) ? $args : array();
$language = isset($email_args['language']) && $email_args['language'] === 'ar' ? 'ar' : 'en';
$direction = $language === 'ar' ? 'rtl' : 'ltr';
$application = isset($email_args['application']) && is_array($email_args['application']) ? $email_args['application'] : array();
$show_details = !empty($email_args['show_details']);
?>
<!doctype html>
<html lang="<?php echo esc_attr($language); ?>" dir="<?php echo esc_attr($direction); ?>">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo esc_html($email_args['heading'] ?? ''); ?></title>
  </head>
  <body style="margin:0;padding:0;background:#f3edf9;color:#2d1265;font-family:Arial,Helvetica,sans-serif;direction:<?php echo esc_attr($direction); ?>;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="width:100%;background:#f3edf9;">
      <tr>
        <td align="center" style="padding:32px 16px;">
          <table role="presentation" width="620" cellspacing="0" cellpadding="0" border="0" style="width:100%;max-width:620px;background:#ffffff;border-radius:24px;overflow:hidden;box-shadow:0 12px 36px rgba(45,18,101,.10);">
            <tr>
              <td style="height:8px;background:#ff4fb3;font-size:0;line-height:0;">&nbsp;</td>
            </tr>
            <tr>
              <td style="padding:32px 40px 16px;text-align:<?php echo $language === 'ar' ? 'right' : 'left'; ?>;">
                <img src="<?php echo esc_url($email_args['logo_url'] ?? ''); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" width="112" style="display:block;width:112px;max-width:100%;height:auto;border:0;" />
              </td>
            </tr>
            <tr>
              <td style="padding:12px 40px 30px;text-align:<?php echo $language === 'ar' ? 'right' : 'left'; ?>;">
                <h1 style="margin:0 0 14px;color:#3f147c;font-size:28px;line-height:1.3;font-weight:700;">
                  <?php echo esc_html($email_args['heading'] ?? ''); ?>
                </h1>
                <div style="margin:0;color:#4d3b66;font-size:15px;line-height:1.8;">
                  <?php echo nl2br(esc_html($email_args['message'] ?? '')); ?>
                </div>
              </td>
            </tr>

            <?php if ($show_details) : ?>
              <tr>
                <td style="padding:0 40px 12px;">
                  <?php foreach ($application as $step_name => $fields) : ?>
                    <?php if (is_array($fields)) : ?>
                      <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="width:100%;margin:0 0 18px;border:1px solid #e8def3;border-radius:14px;border-collapse:separate;overflow:hidden;">
                        <tr>
                          <td colspan="2" style="padding:12px 16px;background:#f7f2fb;color:#3f147c;font-size:14px;font-weight:700;">
                            <?php echo esc_html(ghalya_readable_label(preg_replace('/^(en|ar):/', '', $step_name))); ?>
                          </td>
                        </tr>
                        <?php foreach ($fields as $field_name => $value) : ?>
                          <tr>
                            <td style="width:38%;padding:11px 16px;border-top:1px solid #eee7f5;color:#654d85;font-size:12px;font-weight:700;vertical-align:top;">
                              <?php echo esc_html(ghalya_readable_label($field_name)); ?>
                            </td>
                            <td style="padding:11px 16px;border-top:1px solid #eee7f5;color:#2f2440;font-size:13px;line-height:1.5;vertical-align:top;word-break:break-word;">
                              <?php echo esc_html(is_array($value) ? implode(', ', array_map('strval', $value)) : (string) $value); ?>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      </table>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </td>
              </tr>
            <?php endif; ?>

            <?php if (!empty($email_args['button_url']) && !empty($email_args['button_label'])) : ?>
              <tr>
                <td style="padding:4px 40px 34px;text-align:<?php echo $language === 'ar' ? 'right' : 'left'; ?>;">
                  <a href="<?php echo esc_url($email_args['button_url']); ?>" style="display:inline-block;padding:13px 24px;border-radius:999px;background:#4a0c82;color:#ffffff;text-decoration:none;font-size:15px;font-weight:700;">
                    <?php echo esc_html($email_args['button_label']); ?>
                  </a>
                </td>
              </tr>
            <?php endif; ?>

            <tr>
              <td style="padding:20px 40px;background:#3f147c;color:#ffffff;text-align:center;font-size:12px;line-height:1.6;">
                <?php echo nl2br(esc_html($email_args['footer'] ?? '')); ?>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </body>
</html>
