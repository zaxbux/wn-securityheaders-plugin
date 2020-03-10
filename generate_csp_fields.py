fetch_template = """
        ### {name}-src ###
        csp[{name}][none]:
            type: checkbox
            label: none
            comment: zaxbux.securityheaders::lang.fields.settings.csp_none
            tab: {name}-src
            span: storm
            cssClass: col-xs-3

        csp[{name}][self]:
            type: checkbox
            label: self
            comment: zaxbux.securityheaders::lang.fields.settings.csp_self
            tab: {name}-src
            span: storm
            cssClass: col-xs-3

        csp[{name}][unsafe-eval]:
            type: checkbox
            label: unsafe-eval
            comment: zaxbux.securityheaders::lang.fields.settings.csp_unsafe_eval
            tab: {name}-src
            span: storm
            cssClass: col-xs-3

        csp[{name}][unsafe-hashes]:
            type: checkbox
            label: unsafe-hashes
            comment: zaxbux.securityheaders::lang.fields.settings.csp_unsafe_hashes
            tab: {name}-src
            span: storm
            cssClass: col-xs-3
        
        csp[{name}][unsafe-inline]:
            type: checkbox
            label: unsafe-inline
            comment: zaxbux.securityheaders::lang.fields.settings.csp_unsafe_inline
            tab: {name}-src
            span: storm
            cssClass: col-xs-3
        
        csp[{name}][nonce]:
            type: checkbox
            label: nonce
            comment: zaxbux.securityheaders::lang.fields.settings.csp_nonce
            tab: {name}-src
            span: storm
            cssClass: col-xs-3

        csp[{name}][strict-dynamic]:
            type: checkbox
            label: strict-dynamic
            comment: zaxbux.securityheaders::lang.fields.settings.csp_strict_dynamic
            tab: {name}-src
            span: storm
            cssClass: col-xs-3

        csp[{name}][report-sample]:
            type: checkbox
            label: report-sample
            comment: zaxbux.securityheaders::lang.fields.settings.csp_report_sample
            tab: {name}-src
            span: storm
            cssClass: col-xs-3

        csp[{name}][_sources]:
            type: repeater
            tab: {name}-src
            prompt: zaxbux.securityheaders::lang.fields.settings.csp_sources
            titleFrom: value
            groups: $/zaxbux/securityheaders/models/settings/csp-source.yaml
"""

def main():
	directives = [
        'base-uri',
		'default-src',
		'child-src',
		'connect-src',
		'font-src',
		'frame-src',
		'img-src',
		'manifest-src',
		'media-src',
		'object-src',
		'script-src',
		'style-src',
        'form-action'
	]

	for directive in directives:
		print(fetch_template.format(name=directive))

if __name__ == '__main__':
	main()