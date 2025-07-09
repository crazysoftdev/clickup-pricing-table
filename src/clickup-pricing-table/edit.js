import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';
import './editor.scss';

export default function Edit({ attributes, setAttributes }) {
    const { showAIBanner } = attributes;

    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Settings', 'clickup-pricing-table')}>
                    <ToggleControl
                        label={__('Show AI Add-on Banner', 'clickup-pricing-table')}
                        help={
                            showAIBanner
                                ? __('Banner is currently visible.', 'clickup-pricing-table')
                                : __('Banner is hidden.', 'clickup-pricing-table')
                        }
                        checked={showAIBanner}
                        onChange={() => setAttributes({ showAIBanner: !showAIBanner })}
                    />
                </PanelBody>
            </InspectorControls>

            <div {...useBlockProps()}>
                <div className="pricing-table-preview">
                    {/* This is a static preview. The real data is rendered by PHP. */}
                    <div className="plan-column">
                        <h4>Free Forever</h4>
                        <p className="price">FREE</p>
                        <ul>
                            <li>100MB Storage</li>
                            <li>Unlimited Tasks</li>
                            <li>Unlimited Free Plan Members</li>
                            <li>Two-factor Authentication</li>
                            <li>Collaborative Docs</li>
                        </ul>
                    </div>
                    <div className="plan-column plan-column--popular">
                        <h4>Business</h4>
                        <p className="price">$12</p>
                        <ul>
                            <li>Everything in Unlimited, plus:</li>
                            <li>Google SSO</li>
                            <li>Unlimited Teams</li>
                            <li>Custom Exporting</li>
                            <li>Advanced Public Sharing</li>
                        </ul>
                    </div>
                    <div className="plan-column">
                        <h4>Enterprise</h4>
                        <p className="price">Contact Sales</p>
                        <ul>
                            <li>Everything in Business, plus:</li>
                            <li>White Labeling</li>
                            <li>Advanced Permissions</li>
                            <li>Enterprise API</li>
                            <li>Unlimited Custom Roles</li>
                        </ul>
                    </div>
                </div>
                {showAIBanner && (
                    <div className="ai-banner-preview">
                        {__('AI Add-on Banner Preview', 'clickup-pricing-table')}
                    </div>
                )}
            </div>
        </>
    );
}