<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Create HD Wallet</title>

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
                        <button @click="createRandomPhrase()">Create random mnemonic phrase</button>
                    </td>
                </tr>
                <tr>
                    <th>Path</th>
                    <td>
                        <input v-model="path" placeholder="path" />
                    </td>
                </tr>
                <tr>
                    <th></th>
                    <td>
                        <button @click="createWallet()">Create wallet</button>
                        <span>@{{ msg }}</span>
                    </td>
                </tr>
                <tr>
                    <th>Mnemonic Phrase</th>
                    <td>@{{ wallet.phrase }}</td>
                </tr>
                <tr>
                    <th>Path</th>
                    <td>@{{ wallet.path }}</td>
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
                        title: 'Create HD Wallet',
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
                    // 生成随机助记词
                    createRandomPhrase() {
                        // 2.1. 生成随机数，使用随机数生成助记词
                        this.phrase = ethers.utils.entropyToMnemonic(ethers.utils.randomBytes(16))
                    },
                    // 生成钱包
                    createWallet() {
                        // 检查助记词是否有效
                        if (!ethers.utils.isValidMnemonic(this.phrase)) {
                            this.msg = "Mnemonic Phrase invalied."
                            return
                        }

                        // 2.2. 使用助记词生成钱包
                        var wallet = ethers.Wallet.fromMnemonic(this.phrase, this.path)
                        if (wallet) {
                            this.msg = "Wallet created successful."
                            this.wallet.phrase = wallet.mnemonic.phrase
                            this.wallet.path = wallet.mnemonic.path
                            this.wallet.address = wallet.address
                            this.wallet.publicKey = wallet.publicKey
                            this.wallet.privateKey = wallet.privateKey
                        } else {
                            this.msg = "Wallet created failed."
                        }
                        
                        // 1. 直接生成32个字节的数当成私钥用来生成钱包
                        // var wallet = new ethers.Wallet(ethers.utils.randomBytes(32))

                        // 3. 直接生成带有助记词的钱包
                        // var wallet = ethers.Wallet.createRandom()
                    }
                }
            }).mount('#app')
        </script>
    </body>
</html>
