<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Import HD Wallet</title>

        <script src="https://lf6-cdn-tos.bytecdntp.com/cdn/expire-1-M/vue/3.2.31/vue.global.min.js" type="application/javascript"></script>
        <script src="https://lf26-cdn-tos.bytecdntp.com/cdn/expire-1-M/ethers/5.5.4/ethers.umd.min.js" type="application/javascript"></script>

        <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/element-plus/dist/index.css" />
        <script src="//cdn.jsdelivr.net/npm/element-plus"></script>

        <style type="text/css">
            table th { text-align: right; font-weight: normal; padding-right: 20px; }
        </style>
    </head>
    <body class="antialiased">
        <div id="app">
            <h3>@{{ title }}</h3>

            <el-form label-width="auto">
                <el-form-item label="Mnemonic Phrase">
                    <el-input v-model="phrase" placeholder="mnemonic phrase" />
                </el-form-item>

                <el-form-item label=" ">
                    <el-button type="primary" @click="importWallet()">Import wallet</button>
                </el-form-item>
            </el-form>

            <table>
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
                            ElementPlus.ElMessage({
                                message: 'Mnemonic Phrase invalied.',
                                type: 'error',
                            })
                            return
                        }

                        // 2.2. 使用助记词导入钱包
                        let wallet = ethers.Wallet.fromMnemonic(this.phrase, this.path)
                        if (wallet) {
                            this.wallet.phrase = wallet.mnemonic.phrase
                            this.wallet.path = wallet.mnemonic.path
                            this.wallet.address = wallet.address
                            this.wallet.publicKey = wallet.publicKey
                            this.wallet.privateKey = wallet.privateKey

                            ElementPlus.ElMessage({
                                message: 'Wallet import successful.',
                                type: 'success',
                            })
                        } else {
                            ElementPlus.ElMessage({
                                message: 'Wallet import failed.',
                                type: 'error',
                            })
                        }
                    }
                }
            }).use(ElementPlus).mount('#app')
        </script>
    </body>
</html>
