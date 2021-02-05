<!DOCTYPE html>
<!--
Copyright (C) 2018 Lukas Buchs
license https://github.com/lbuchs/WebAuthn/blob/master/LICENSE MIT
-->
<html>
    <head>
        <title>lbuchs/WebAuthn Test</title>
        <meta charset="UTF-8">
        <script>

        /**
         * creates a new FIDO2 registration
         * @returns {undefined}
         */
        function newregistration() {

            if (!window.fetch || !navigator.credentials || !navigator.credentials.create) {
                window.alert('Browser not supported.');
                return;
            }

            // get default args
            window.fetch('server?fn=getCreateArgs' + getGetParams(), {method:'GET',cache:'no-cache'}).then(function(response) {
                return response.json();

                // convert base64 to arraybuffer
            }).then(function(json) {

                // error handling
                if (json.success === false) {
                    throw new Error(json.msg);
                }

                // replace binary base64 data with ArrayBuffer. a other way to do this
                // is the reviver function of JSON.parse()
                recursiveBase64StrToArrayBuffer(json);
                return json;

               // create credentials
            }).then(function(createCredentialArgs) {
                console.log(createCredentialArgs);
                return navigator.credentials.create(createCredentialArgs);

                // convert to base64
            }).then(function(cred) {
                return {
                    clientDataJSON: cred.response.clientDataJSON  ? arrayBufferToBase64(cred.response.clientDataJSON) : null,
                    attestationObject: cred.response.attestationObject ? arrayBufferToBase64(cred.response.attestationObject) : null
                };

                // transfer to server
            }).then(JSON.stringify).then(function(AuthenticatorAttestationResponse) {
                return window.fetch('server?fn=processCreate' + getGetParams(), {method:'POST', body: AuthenticatorAttestationResponse, cache:'no-cache'});

                // convert to JSON
            }).then(function(response) {
                return response.json();

                // analyze response
            }).then(function(json) {
               if (json.success) {
                   window.alert(json.msg || 'registration success');
               } else {
                   throw new Error(json.msg);
               }

               // catch errors
            }).catch(function(err) {
                window.alert(err.message || 'unknown error occured');
            });
        }


        /**
         * checks a FIDO2 registration
         * @returns {undefined}
         */
        function checkregistration() {

            if (!window.fetch || !navigator.credentials || !navigator.credentials.create) {
                window.alert('Browser not supported.');
                return;
            }

            // get default args
            window.fetch('server?fn=getGetArgs' + getGetParams(), {method:'GET',cache:'no-cache'}).then(function(response) {
                return response.json();

                // convert base64 to arraybuffer
            }).then(function(json) {

                // error handling
                if (json.success === false) {
                    throw new Error(json.msg);
                }

                // replace binary base64 data with ArrayBuffer. a other way to do this
                // is the reviver function of JSON.parse()
                recursiveBase64StrToArrayBuffer(json);
                return json;

               // create credentials
            }).then(function(getCredentialArgs) {
                return navigator.credentials.get(getCredentialArgs);

                // convert to base64
            }).then(function(cred) {
                return {
                    id: cred.rawId ? arrayBufferToBase64(cred.rawId) : null,
                    clientDataJSON: cred.response.clientDataJSON  ? arrayBufferToBase64(cred.response.clientDataJSON) : null,
                    authenticatorData: cred.response.authenticatorData ? arrayBufferToBase64(cred.response.authenticatorData) : null,
                    signature : cred.response.signature ? arrayBufferToBase64(cred.response.signature) : null
                };

                // transfer to server
            }).then(JSON.stringify).then(function(AuthenticatorAttestationResponse) {
                return window.fetch('server?fn=processGet' + getGetParams(), {method:'POST', body: AuthenticatorAttestationResponse, cache:'no-cache'});

                // convert to json
            }).then(function(response) {
                return response.json();

                // analyze response
            }).then(function(json) {
               if (json.success) {
                   window.alert(json.msg || 'login success');
               } else {
                   throw new Error(json.msg);
               }

               // catch errors
            }).catch(function(err) {
                window.alert(err.message || 'unknown error occured');
            });
        }

        function clearregistration() {
            window.fetch('server?fn=clearRegistrations' + getGetParams(), {method:'GET',cache:'no-cache'}).then(function(response) {
                return response.json();

            }).then(function(json) {
               if (json.success) {
                   window.alert(json.msg);
               } else {
                   throw new Error(json.msg);
               }
            }).catch(function(err) {
                window.alert(err.message || 'unknown error occured');
            });
        }

        /**
         * convert RFC 1342-like base64 strings to array buffer
         * @param {mixed} obj
         * @returns {undefined}
         */
        function recursiveBase64StrToArrayBuffer(obj) {
            let prefix = '=?BINARY?B?';
            let suffix = '?=';
            if (typeof obj === 'object') {
                for (let key in obj) {
                    if (typeof obj[key] === 'string') {
                        let str = obj[key];
                        if (str.substring(0, prefix.length) === prefix && str.substring(str.length - suffix.length) === suffix) {
                            str = str.substring(prefix.length, str.length - suffix.length);

                            let binary_string = window.atob(str);
                            let len = binary_string.length;
                            let bytes = new Uint8Array(len);
                            for (let i = 0; i < len; i++)        {
                                bytes[i] = binary_string.charCodeAt(i);
                            }
                            obj[key] = bytes.buffer;
                        }
                    } else {
                        recursiveBase64StrToArrayBuffer(obj[key]);
                    }
                }
            }
        }

        /**
         * Convert a ArrayBuffer to Base64
         * @param {ArrayBuffer} buffer
         * @returns {String}
         */
        function arrayBufferToBase64(buffer) {
            let binary = '';
            let bytes = new Uint8Array(buffer);
            let len = bytes.byteLength;
            for (let i = 0; i < len; i++) {
                binary += String.fromCharCode( bytes[ i ] );
            }
            return window.btoa(binary);
        }

        /**
         * Get URL parameter
         * @returns {String}
         */
        function getGetParams() {
            let url = '';
            url += '&apple=' + (document.getElementById('cert_apple').checked ? '1' : '0');
            url += '&yubico=' + (document.getElementById('cert_yubico').checked ? '1' : '0');
            url += '&solo=' + (document.getElementById('cert_solo').checked ? '1' : '0');
            url += '&hypersecu=' + (document.getElementById('cert_hypersecu').checked ? '1' : '0');
            url += '&google=' + (document.getElementById('cert_google').checked ? '1' : '0');
            url += '&microsoft=' + (document.getElementById('cert_microsoft').checked ? '1' : '0');

            url += '&requireResidentKey=' + (document.getElementById('requireResidentKey').checked ? '1' : '0');


            //<!--$allowUsb=true, $allowNfc=true, $allowBle=true, $allowInternal-->
            url += '&type_usb=' + (document.getElementById('type_usb').checked ? '1' : '0');
            url += '&type_nfc=' + (document.getElementById('type_nfc').checked ? '1' : '0');
            url += '&type_ble=' + (document.getElementById('type_ble').checked ? '1' : '0');
            url += '&type_int=' + (document.getElementById('type_int').checked ? '1' : '0');

            url += '&fmt_android-key=' + (document.getElementById('fmt_android-key').checked ? '1' : '0');
            url += '&fmt_android-safetynet=' + (document.getElementById('fmt_android-safetynet').checked ? '1' : '0');
            url += '&fmt_apple=' + (document.getElementById('fmt_apple').checked ? '1' : '0');
            url += '&fmt_fido-u2f=' + (document.getElementById('fmt_fido-u2f').checked ? '1' : '0');
            url += '&fmt_none=' + (document.getElementById('fmt_none').checked ? '1' : '0');
            url += '&fmt_packed=' + (document.getElementById('fmt_packed').checked ? '1' : '0');
            url += '&fmt_tpm=' + (document.getElementById('fmt_tpm').checked ? '1' : '0');
            url += '&rpId=' + encodeURIComponent(document.getElementById('rpId').value);

            if (document.getElementById('userVerification_required').checked) {
                url += '&userVerification=required';

            } else if (document.getElementById('userVerification_preferred').checked) {
                url += '&userVerification=preferred';

            } else if (document.getElementById('userVerification_discouraged').checked) {
                url += '&userVerification=discouraged';
            }

            return url;
        }

        /**
         * force https on load
         * @returns {undefined}
         */
        window.onload = function() {
            if (location.protocol !== 'https:' && location.host !== 'localhost') {
                location.href = location.href.replace('http', 'https');
            }
            if (!document.getElementById('rpId').value) {
                document.getElementById('rpId').value = location.hostname;
            }
        }

        </script>
    </head>
    <body style="font-family:sans-serif;margin: 0 20px;">
        <h1 style="margin: 40px 10px 2px 0;">lbuchs/WebAuthn</h1>
        <div style="font-style: italic;">A simple PHP WebAuthn (FIDO2) server library.</div>
        <div>&nbsp;</div>
        <div>&nbsp;</div>
        <div>Simple working demo for the <a href="https://github.com/lbuchs/WebAuthn">lbuchs/WebAuthn</a> library.</div>
        <div>
            <div>&nbsp;</div>
            <table>
                <tbody><tr>
                        <td>
                            <button type="button" onclick="newregistration()">&#10133; new registration</button>
                        </td>
                        <td>
                            <button type="button" onclick="checkregistration()">&#10068; check registration</button>
                        </td>
                        <td>
                            <button type="button" onclick="clearregistration()">&#9249; clear all registrations</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div>&nbsp;</div>

            <div>
                <input type="checkbox" id="requireResidentKey" name="requireResidentKey">
                <label for="requireResidentKey">Use Client-side-resident Public Key Credential Source</label>
            </div>

            <div>&nbsp;</div>
            <div style="font-weight: bold">Relying Party</div>
            <p style="margin:0 0 5px 0;font-size:0.9em;font-style: italic;">A valid domain string that identifies the
                WebAuthn Relying Party<br/>on whose behalf a given registration or authentication ceremony is being performed.</p>
            <div>
                <label for="rpId">RP ID:</label>
                <input type="text" id="rpId" name="rpId" value="">
            </div>

            <div>&nbsp;</div>
            <div style="font-weight: bold">attestation statement format</div>
            <div>
                <input type="checkbox" id="fmt_android-key" name="fmt_android-key" checked>
                <label for="fmt_android-key">android-key</label>
            </div>

            <div>
                <input type="checkbox" id="fmt_android-safetynet" name="fmt_android-safetynet" checked>
                <label for="fmt_android-safetynet">android-safetynet</label>
            </div>

            <div>
                <input type="checkbox" id="fmt_apple" name="fmt_apple" checked>
                <label for="fmt_apple">apple</label>
            </div>

            <div>
                <input type="checkbox" id="fmt_fido-u2f" name="fmt_fido-u2f" checked>
                <label for="fmt_fido-u2f">fido-u2f</label>
            </div>

            <div>
                <input type="checkbox" id="fmt_none" name="fmt_none" checked>
                <label for="fmt_none">none</label>
            </div>

            <div>
                <input type="checkbox" id="fmt_packed" name="fmt_packed" checked>
                <label for="fmt_packed">packed</label>
            </div>

            <div>
                <input type="checkbox" id="fmt_tpm" name="fmt_tpm" checked>
                <label for="fmt_tpm">tpm</label>
            </div>

            <div>&nbsp;</div>
            <div style="font-weight: bold">user verification</div>
            <div>
                <input type="radio" id="userVerification_required" name="userVerification">
                <label for="userVerification_required">required <i style="font-size: 0.8em;">User verification is required (e.g. by pin), the operation will fail if the response does not have the UV flag.</i></label>
            </div>

            <div>
                <input type="radio" id="userVerification_preferred" name="userVerification">
                <label for="userVerification_preferred">preferred <i style="font-size: 0.8em;">user verification is prefered, the operation will not fail if the response does not have the UV flag.</i></label>
            </div>

            <div>
                <input type="radio" id="userVerification_discouraged" name="userVerification" checked>
                <label for="userVerification_discouraged">discouraged <i style="font-size: 0.8em;">user verification should not be employed as to minimize the user interaction during the process.</i></label>
            </div>

            <div>&nbsp;</div>
            <div style="font-weight: bold">type of authenticator</div>
            <div>
                <input type="checkbox" id="type_usb" name="type_usb" checked>
                <label for="type_usb">USB</label>
            </div>
            <div>
                <input type="checkbox" id="type_nfc" name="type_nfc" checked>
                <label for="type_nfc">NFC</label>
            </div>
            <div>
                <input type="checkbox" id="type_ble" name="type_ble" checked>
                <label for="type_ble">BLE</label>
            </div>
            <div>
                <input type="checkbox" id="type_int" name="type_int" checked>
                <label for="type_int">internal <i style="font-size: 0.8em;">Windows Hello, Android SafetyNet, Apple, ...</i></label>
            </div>


            <div>&nbsp;</div>
            <div style="font-weight: bold">root certificates</div>

            <div>
                <input type="checkbox" id="cert_apple" name="apple" checked>
                <label for="cert_apple">Accept keys signed by apple root ca</label>
            </div>

            <div>
                <input type="checkbox" id="cert_yubico" name="yubico" checked>
                <label for="cert_yubico">Accept keys signed by yubico root ca</label>
            </div>

            <div>
                <input type="checkbox" id="cert_solo" name="solo" checked>
                <label for="cert_solo">Accept keys signed by solokeys root ca</label>
            </div>

            <div>
                <input type="checkbox" id="cert_hypersecu" name="hypersecu" checked>
                <label for="cert_hypersecu">Accept keys signed by hypersecu root ca</label>
            </div>

            <div>
                <input type="checkbox" id="cert_google" name="google" checked>
                <label for="cert_google">Accept keys signed by google root ca</label>
            </div>

            <div>
                <input type="checkbox" id="cert_microsoft" name="microsoft" checked>
                <label for="cert_microsoft">Accept keys signed by Microsofts collection of trusted TPM root ca</label>
            </div>
            <div style="font-size: 0.7em">(Nothing checked = accept all)</div>
            <div>&nbsp;</div>
            <div>If you select a root ca, direct attestation is required to validate your client with the root.<br>
                The browser may warn you that he will provide informations about your device.<br>
                When not checking against any root ca (deselect all certificates),
                the client may change the assertion from the authenticator (for instance, using an anonymization CA),<br>
                the browser may not warn about providing informations about your device.
           </div>

        </div>
    </body>
</html>
