<?php
defined('APP_ROOT') || exit;

const ENQUIRY_LIMITS = ['name' => 120, 'email' => 200, 'subject' => 200, 'message' => 5000];
const ENQUIRIES_PER_HOUR = 5;

/**
 * Validates an enquiry, stores it in the database and emails it.
 * Returns ['ok' => true] or ['ok' => false, 'error' => string, 'status' => int].
 */
function submit_enquiry(array $input): array
{
    // Honeypot: bots fill hidden fields. Pretend success.
    if (!empty($input['company'])) {
        return ['ok' => true];
    }

    $data = [];
    foreach (ENQUIRY_LIMITS as $field => $max) {
        $value = trim(is_string($input[$field] ?? null) ? $input[$field] : '');
        if ($value === '') {
            return ['ok' => false, 'status' => 400, 'error' => "Please provide your $field."];
        }
        if (mb_strlen($value) > $max) {
            return ['ok' => false, 'status' => 400, 'error' => "Your $field is too long."];
        }
        $data[$field] = $value;
    }
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        return ['ok' => false, 'status' => 400, 'error' => 'Please provide a valid email address.'];
    }

    $canStore = db_configured();
    $canEmail = (string) config('mail.to', '') !== '';
    if (!$canStore && !$canEmail) {
        return [
            'ok' => false,
            'status' => 503,
            'error' => 'The enquiry service is not yet connected. Please use the institutional contact details instead.',
        ];
    }

    $stored = false;
    if ($canStore) {
        try {
            $recent = db()->prepare('SELECT COUNT(*) FROM enquiries WHERE ip = ? AND created_at > NOW() - INTERVAL 1 HOUR');
            $recent->execute([client_ip()]);
            if ((int) $recent->fetchColumn() >= ENQUIRIES_PER_HOUR) {
                return ['ok' => false, 'status' => 429, 'error' => 'Too many enquiries from your connection. Please try again later.'];
            }
            db()->prepare('INSERT INTO enquiries (name, email, subject, message, ip) VALUES (?, ?, ?, ?, ?)')
                ->execute([$data['name'], $data['email'], $data['subject'], $data['message'], client_ip()]);
            $stored = true;
        } catch (Throwable $e) {
            error_log('Enquiry insert failed: ' . $e->getMessage());
        }
    }

    $emailed = $canEmail && send_enquiry_email($data);

    if (!$stored && !$emailed) {
        return ['ok' => false, 'status' => 502, 'error' => 'Sorry, your enquiry could not be sent. Please try again later.'];
    }
    return ['ok' => true];
}

function send_enquiry_email(array $d): bool
{
    $to = (string) config('mail.to');
    // Only trust the configured site URL here — the request's Host header is attacker-controlled.
    $siteUrl = rtrim((string) config('site_url', ''), '/');
    $from = (string) config('mail.from', '');
    if (!filter_var($from, FILTER_VALIDATE_EMAIL) && $siteUrl !== '') {
        $from = 'no-reply@' . preg_replace('/^www\./', '', (string) parse_url($siteUrl, PHP_URL_HOST));
    }
    $from = filter_var($from, FILTER_VALIDATE_EMAIL) ? $from : '';

    $subject = 'Website enquiry: ' . str_replace(["\r", "\n"], ' ', $d['subject']);
    $body = "A new enquiry was sent from the website contact form.\n\n"
        . "Name:    {$d['name']}\n"
        . "Email:   {$d['email']}\n"
        . "Subject: {$d['subject']}\n\n"
        . $d['message'] . "\n\n"
        . "—\nReply to this email to respond directly."
        . ($siteUrl !== '' ? " All enquiries are also listed at $siteUrl/admin/enquiries" : '') . "\n";

    $headers = [
        'Reply-To' => $d['email'], // validated with FILTER_VALIDATE_EMAIL, so no header injection
        'MIME-Version' => '1.0',
        'Content-Type' => 'text/plain; charset=UTF-8',
        'Content-Transfer-Encoding' => '8bit',
    ];
    if ($from !== '') {
        $headers = ['From' => 'Dr. Malek Gaashan website <' . $from . '>'] + $headers;
    }

    try {
        $sent = $from !== ''
            ? mail($to, mb_encode_mimeheader($subject, 'UTF-8'), $body, $headers, '-f' . $from)
            : mail($to, mb_encode_mimeheader($subject, 'UTF-8'), $body, $headers);
    } catch (Throwable $e) {
        $sent = false;
        error_log('Enquiry email failed: ' . $e->getMessage());
    }
    if (!$sent) {
        error_log('Enquiry email could not be handed to the mail server.');
    }
    return $sent;
}
