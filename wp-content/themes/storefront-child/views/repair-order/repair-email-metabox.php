<?php
/**
 * View: Send Repair Email metabox
 * Included by repair_order_email_metabox_callback() in add-meta-boxes.php
 *
 * @var WP_Post $post Current order_repair post.
 */

$repair_post_id = $post->ID;
$log            = get_post_meta($repair_post_id, '_repair_email_log', true);
if (!is_array($log)) {
    $log = array();
}
$recent_log = array_slice($log, 0, 5);
?>
<?php wp_nonce_field('matrix_repair_email_nonce', 'repair_email_nonce'); ?>

<p>
    <button type="button"
            id="matrix-send-repair-email"
            class="button button-primary"
            style="width:100%;">
        Send Notification Email
    </button>
</p>

<div id="matrix-email-status" style="margin-top:8px;"></div>

<?php if (!empty($recent_log)) : ?>
    <hr>
    <h4 style="margin-bottom:6px;">Send History</h4>
    <table style="width:100%;border-collapse:collapse;font-size:12px;">
        <thead>
        <tr>
            <th style="text-align:left;padding:3px 4px;border-bottom:1px solid #ddd;">Date/Time</th>
            <th style="text-align:left;padding:3px 4px;border-bottom:1px solid #ddd;">Result</th>
            <th style="text-align:left;padding:3px 4px;border-bottom:1px solid #ddd;">Sent by</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($recent_log as $entry) : ?>
            <tr>
                <td style="padding:3px 4px;vertical-align:top;">
                    <?php echo esc_html($entry['time']); ?>
                </td>
                <td style="padding:3px 4px;vertical-align:top;">
                    <?php if (!empty($entry['result'])) : ?>
                        <span style="color:#0a6b0a;">&#10003; Sent</span>
                    <?php else : ?>
                        <span style="color:#cc0000;">&#10007; Failed</span>
                    <?php endif; ?>
                </td>
                <td style="padding:3px 4px;vertical-align:top;">
                    <?php echo esc_html($entry['user_name']); ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else : ?>
    <p style="color:#777;font-size:12px;margin-top:8px;">No emails sent yet.</p>
<?php endif; ?>

<script>
    (function ($) {
        var postId  = <?php echo (int) $repair_post_id; ?>;
        var nonce   = <?php echo wp_json_encode(wp_create_nonce('matrix_repair_email_nonce')); ?>;
        var $btn    = $('#matrix-send-repair-email');
        var $status = $('#matrix-email-status');

        $btn.on('click', function () {
            $btn.prop('disabled', true).text('Sending\u2026');
            $status.html('');

            wp.ajax.post('matrix_resend_repair_email', {
                nonce:   nonce,
                post_id: postId
            }).done(function (data) {
                $status.html('<p style="color:#0a6b0a;margin:0;">\u2713 ' + (data.message || 'Email sent.') + '</p>');
                setTimeout(function () {
                    location.reload();
                }, 2000);
            }).fail(function (data) {
                var msg = (data && data.message) ? data.message : 'An error occurred.';
                $status.html('<p style="color:#cc0000;margin:0;">\u2717 ' + msg + '</p>');
                $btn.prop('disabled', false).text('Send Notification Email');
            });
        });
    }(jQuery));
</script>
