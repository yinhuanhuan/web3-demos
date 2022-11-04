<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Import HD Wallet</title>

        <script src="https://lf6-cdn-tos.bytecdntp.com/cdn/expire-1-M/vue/3.2.31/vue.global.min.js" type="application/javascript"></script>
        <script src="https://lf26-cdn-tos.bytecdntp.com/cdn/expire-1-M/ethers/5.5.4/ethers.umd.min.js" type="application/javascript"></script>
    </head>
    <body class="antialiased">
        <div id="app">
            <h3>@{{ title }}</h3>
            
            <table>
                <tr>
                    <th>Mnemonic Phrase</th>
                    <td>
                        <input v-model="phrase" placeholder="mnemonic phrase" />
                    </td>
                </tr>
                <tr>
                    <th></th>
                    <td>
                        <button @click="importWallet()">Import wallet</button>
                        <span>@{{ msg }}</span>
                    </td>
                </tr>
                <tr>
                    <th>Mnemonic Phrase</th>
                    <td>@{{ wallet.phrase }}</td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td>@{{ wallet.address }}</td>
                </tr>
                <tr>
                    <th>Public Key</th>
                    <td>@{{ wallet.publicKey }}</td>
                </tr>
                <tr>
                    <th>Private Key</th>
                    <td>@{{ wallet.privateKey }}</td>
                </tr>
            </table>
        </div>

        <script>
            const { createApp } = Vue

            createApp({
                data() {
                    return {
                        title: 'Import HD Wallet',
                        msg: "",
                        phrase: "",
                        path: "m/44'/60'/0'/0/0",
                        wallet: {
                            phrase: "",
                            path: "",
                            address: "",
                            publicKey: "",
                            privateKey: "",
                        },
                    }
                },
                methods: {
                    // 导入钱包
                    importWallet() {
                        // 检查助记词是否有效
                        if (!ethers.utils.isValidMnemonic(this.phrase)) {
                            this.msg = "Mnemonic Phrase invalied."
                            return
                        }

                        // 2.2. 使用助记词导入钱包
                        let wallet = ethers.Wallet.fromMnemonic(this.phrase, this.path)
                        if (wallet) {
                            this.msg = "Wallet import successful."
                            this.wallet.phrase = wallet.mnemonic.phrase
                            this.wallet.path = wallet.mnemonic.path
                            this.wallet.address = wallet.address
                            this.wallet.publicKey = wallet.publicKey
                            this.wallet.privateKey = wallet.privateKey
                        } else {
                            this.msg = "Wallet import failed."
                        }
                    }
                }
            }).mount('#app')
        </script>
    </body>
</html>
