<?php
/**
 * Testi legali in inglese.
 *
 * Non sono gettext ma contenuto di pagina: con Polylang la versione
 * inglese e' una pagina a se', con il suo record nel database, e non
 * una traduzione a runtime di quella italiana. Qui stanno i corpi usati
 * per crearle.
 *
 * I segnaposto {{EXP_*}} sono gli stessi della versione italiana e
 * vengono sostituiti da experiences_legal_filter_content() al momento
 * del rendering, quindi ragione sociale, sede, P.IVA e hosting si
 * compilano una volta sola nel Personalizzatore e valgono per entrambe
 * le lingue.
 *
 * @package experiences-srl
 */

defined( 'ABSPATH' ) || exit;

function experiences_legal_template_privacy_en() {
	$home = esc_url( home_url( '/' ) );
	return <<<HTML
<p><em>Last updated: {{EXP_ULTIMA_MODIFICA}}.</em></p>

<h2>Data controller</h2>
<p><strong>{{EXP_RAGIONE_SOCIALE}}</strong><br>Registered office: {{EXP_SEDE}}<br>VAT number: {{EXP_PIVA}}<br>Email: <a href="mailto:naplesexperiences@gmail.com">naplesexperiences@gmail.com</a><br>Website: <a href="{$home}">{$home}</a></p>

<h2>What we collect</h2>
<p>We only collect the data you give us yourself by filling in the contact form:</p>
<ul>
<li>First and last name</li>
<li>Email address</li>
<li>Type of business (hotel, agency and so on)</li>
<li>The plan you are interested in</li>
<li>The content of your message</li>
</ul>
<p>With your consent we also collect anonymous browsing data through analytics cookies (see the Cookie Policy).</p>

<h2>Why we process it</h2>
<ul>
<li>To answer requests for consultations and enquiries</li>
<li>To send commercial communications (only with your explicit consent)</li>
<li>To improve the site through aggregated traffic analysis</li>
<li>To meet our legal obligations</li>
</ul>

<h2>Legal basis</h2>
<ul>
<li><strong>Pre-contractual measures</strong> (art. 6.1.b GDPR) for consultation requests</li>
<li><strong>Consent</strong> (art. 6.1.a GDPR) for non-essential cookies and the newsletter</li>
<li><strong>Legal obligation</strong> (art. 6.1.c GDPR) for accounting and tax retention</li>
</ul>

<h2>Retention</h2>
<p>Contact data is kept for as long as it takes to deal with your request, and afterwards for a maximum of <strong>24 months</strong>, unless accounting or contractual needs require longer.</p>

<h2>Recipients</h2>
<p>Your data may be processed by:</p>
<ul>
<li><strong>Google LLC</strong> (Gmail) — the controller's email provider</li>
<li><strong>{{EXP_HOSTING}}</strong> — website hosting</li>
<li><strong>Cal.com / Calendly</strong> — for booking calls (only the data you provide when you book)</li>
<li>Consultants, our accountant and the competent authorities — only where the law requires it</li>
</ul>
<p>We never sell your data to third parties.</p>

<h2>Transfers outside the EU</h2>
<p>Some providers (Google, for instance) may process data in the United States. Any transfer takes place under the EU–US Data Privacy Framework or under Standard Contractual Clauses.</p>

<h2>Your rights</h2>
<p>You can exercise the following rights at any time by writing to <a href="mailto:naplesexperiences@gmail.com">naplesexperiences@gmail.com</a>:</p>
<ul>
<li>Access to your data (art. 15 GDPR)</li>
<li>Rectification (art. 16 GDPR)</li>
<li>Erasure (art. 17 GDPR)</li>
<li>Restriction of processing (art. 18 GDPR)</li>
<li>Portability (art. 20 GDPR)</li>
<li>Objection (art. 21 GDPR)</li>
<li>Withdrawal of consent at any time</li>
</ul>
<p>You also have the right to lodge a complaint with the supervisory authority: the <a href="https://www.garanteprivacy.it" rel="noopener" target="_blank">Italian Data Protection Authority</a>.</p>

<h2>Changes</h2>
<p>Any changes to this privacy policy will be published on this page. We invite you to check it from time to time.</p>
HTML;
}

function experiences_legal_template_cookie_en() {
	$dominio = esc_html( wp_parse_url( home_url(), PHP_URL_HOST ) );
	$cmp     = function_exists( 'experiences_cmp_attivo' ) ? experiences_cmp_attivo() : '';

	// Stessa logica della versione italiana: il nome del cookie che
	// memorizza la scelta dipende da chi raccoglie il consenso.
	if ( 'cookieadmin' === $cmp ) {
		$riga_consenso = "<tr><td>cookieadmin_consent</td><td>{$dominio}</td><td>Stores your cookie consent preferences</td><td>12 months</td></tr>";
	} elseif ( $cmp ) {
		$riga_consenso = "<tr><td>Consent cookie set by the plugin in use</td><td>{$dominio}</td><td>Stores your cookie consent preferences</td><td>See the preferences panel</td></tr>";
	} else {
		$riga_consenso = "<tr><td>exp_consent_v1</td><td>{$dominio}</td><td>Stores your cookie consent preferences</td><td>12 months</td></tr>";
	}

	return <<<HTML
<p><em>Last updated: {{EXP_ULTIMA_MODIFICA}}.</em></p>

<p>This website uses cookies to work properly, to analyse traffic and, with your consent, to show personalised content. You can change your preferences at any time by clicking <strong>"Cookie preferences"</strong> at the foot of any page.</p>

<h2>What a cookie is</h2>
<p>A cookie is a small text file your browser stores on your device. It remembers information between one visit and the next — your language, whether you are signed in, your consent choices.</p>

<h2>The categories we use</h2>

<h3>1. Essential cookies (always on)</h3>
<p>Needed for the site to work. Without them some functions would not be available.</p>
<table>
<thead><tr><th>Name</th><th>Provider</th><th>Purpose</th><th>Duration</th></tr></thead>
<tbody>
{$riga_consenso}
<tr><td>wordpress_*</td><td>WordPress</td><td>Session handling for signed-in users (admin)</td><td>Session</td></tr>
</tbody>
</table>

<h3>2. Analytics cookies (opt-in)</h3>
<p>They help us understand how people use the site, in anonymous and aggregated form. Switched on only after your explicit consent.</p>
<table>
<thead><tr><th>Name</th><th>Provider</th><th>Purpose</th><th>Duration</th></tr></thead>
<tbody>
<tr><td>_ga, _ga_*</td><td>Google Analytics (Google LLC)</td><td>Aggregated traffic statistics</td><td>13 months</td></tr>
<tr><td>_clck, _clsk</td><td>Microsoft Clarity (Microsoft Corp.)</td><td>Anonymous heatmaps and session recordings</td><td>12 months</td></tr>
</tbody>
</table>

<h3>3. Marketing cookies (opt-in)</h3>
<p>Used to show more relevant advertising on other sites. Switched on only after your explicit consent.</p>
<table>
<thead><tr><th>Name</th><th>Provider</th><th>Purpose</th><th>Duration</th></tr></thead>
<tbody>
<tr><td>_fbp</td><td>Meta Platforms Inc.</td><td>Remarketing on Facebook and Instagram</td><td>3 months</td></tr>
<tr><td>_gcl_au</td><td>Google Ads</td><td>Google Ads conversion attribution</td><td>3 months</td></tr>
</tbody>
</table>

<h2>Changing your preferences</h2>
<p>You can change your choices at any time by clicking <a href="#" data-cookie-settings>Cookie preferences</a> at the foot of the page. You can also disable or delete cookies directly in your browser:</p>
<ul>
<li><a href="https://support.google.com/chrome/answer/95647" rel="noopener" target="_blank">Google Chrome</a></li>
<li><a href="https://support.mozilla.org/en-US/kb/clear-cookies-and-site-data-firefox" rel="noopener" target="_blank">Mozilla Firefox</a></li>
<li><a href="https://support.apple.com/en-gb/guide/safari/sfri11471/mac" rel="noopener" target="_blank">Safari</a></li>
<li><a href="https://support.microsoft.com/en-us/microsoft-edge" rel="noopener" target="_blank">Microsoft Edge</a></li>
</ul>
<p>Note: disabling essential cookies may stop parts of the site from working.</p>

<h2>More information</h2>
<p>See our <a href="/en/privacy/">Privacy Policy</a> or write to us at <a href="mailto:naplesexperiences@gmail.com">naplesexperiences@gmail.com</a>.</p>
HTML;
}

function experiences_legal_template_terms_en() {
	return <<<HTML
<p><em>Last updated: {{EXP_ULTIMA_MODIFICA}}.</em></p>

<p>These terms and conditions govern the use of naplesexperiences.com and of the services offered by Experiences Srl.</p>

<h2>1. About the provider</h2>
<p><strong>{{EXP_RAGIONE_SOCIALE}}</strong><br>Registered office: {{EXP_SEDE}}<br>VAT number: {{EXP_PIVA}}</p>

<h2>2. What the services cover</h2>
<p>Experiences Srl provides digital services for the tourism sector, including website development, SEO and SEM marketing, channel manager and OTA administration, and AI virtual assistants.</p>

<h2>3. Use of the site</h2>
<p>You agree to use this site lawfully, respecting applicable law and the rights of others. The following are not allowed:</p>
<ul>
<li>Attempting to access restricted areas</li>
<li>Any activity that could damage the site or other users</li>
<li>Copying or reproducing content without permission</li>
</ul>

<h2>4. Intellectual property</h2>
<p>All content on this site — text, images, logos and design — belongs to Experiences Srl or to its respective owners and is protected by copyright law.</p>

<h2>5. Limitation of liability</h2>
<p>Experiences Srl works to provide accurate information but does not warrant that it is free of errors. We are not liable for any damage arising from use of the site or from being unable to access it.</p>

<h2>6. Changes</h2>
<p>Experiences Srl reserves the right to amend these terms at any time. Changes take effect when published on this page.</p>

<h2>7. Governing law and jurisdiction</h2>
<p>These terms are governed by Italian law. The courts of Naples have exclusive jurisdiction over any dispute.</p>

<h2>8. Contact</h2>
<p>For any question about these terms: <a href="mailto:naplesexperiences@gmail.com">naplesexperiences@gmail.com</a></p>
HTML;
}
