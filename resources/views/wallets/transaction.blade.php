<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Wallet Address</title>

        <script src="https://lf6-cdn-tos.bytecdntp.com/cdn/expire-1-M/vue/3.2.31/vue.global.min.js" type="application/javascript"></script>
        <script src="https://lf26-cdn-tos.bytecdntp.com/cdn/expire-1-M/ethers/5.5.4/ethers.umd.min.js" type="application/javascript"></script>
        <script src="https://lf9-cdn-tos.bytecdntp.com/cdn/expire-1-M/axios/0.26.0/axios.min.js" type="application/javascript"></script>
    </head>
    <body class="antialiased">
        <div id="app">
            <h3>@{{ title }}</h3>
            
            <table>
                <tr>
                    <th>Amount</th>
                    <td>
                        <input v-model="amount" placeholder="amount" />
                        <span>wei</span>
                    </td>
                </tr>
                <tr>
                    <th>Address To</th>
                    <td>
                        <input v-model="addressTo" placeholder="address to" />
                    </td>
                </tr>
                <tr>
                    <th>Address From</th>
                    <td>
                        <input v-model="addressFrom" placeholder="address from" />
                    </td>
                </tr>
                <tr>
                    <th>Private Key</th>
                    <td>
                        <input v-model="privateKey" placeholder="private key" />
                    </td>
                </tr>
                <tr>
                    <th>Nonce</th>
                    <td>
                        <input v-model="nonce" placeholder="nonce" />
                    </td>
                </tr>
                <tr>
                    <th>Gas Limit</th>
                    <td>
                        <input v-model="gasLimit" placeholder="gas limit" />
                    </td>
                </tr>
                <tr>
                    <th>Gas Price</th>
                    <td>
                        <input v-model="gasPrice" placeholder="gas price" />
                    </td>
                </tr>
                <tr>
                    <th></th>
                    <td>
                        <button @click="sendTransaction()">Send Transaction</button>
                        <span>@{{ msg }}</span>
                    </td>
                </tr>
                <tr>
                    <th>Tx</th>
                    <td>@{{ tx }}</td>
                </tr>
                <tr>
                    <th>Tx Detail</th>
                    <td>
                        <a :href="txURL" target="_blank">@{{ txURL }}</a>
                    </td>
                </tr>
            </table>
        </div>

        <script>
            const { createApp } = Vue

            createApp({
                data() {
                    return {
                        title: 'Send Transaction',
                        msg: '',
                        amount: 0,
                        addressTo: '',
                        addressFrom: '',
                        privateKey: '',
                        nonce: 0,
                        gasLimit: 1000000,
                        gasPrice: 33721725041,
                        tx: '',
                        txURL: '',
                    }
                },
                methods: {
                    // 发送交易
                    async sendTransaction() {
                        // 检查转入地址是否为钱包地址
                        if (!ethers.utils.isAddress(this.addressTo)) {
                            this.msg = 'Address To format error.'
                            return
                        }

                        // 检查转出地址是否为钱包地址
                        if (!ethers.utils.isAddress(this.addressFrom)) {
                            this.msg = 'Address From format error.'
                            return
                        }

                        // 1. 构造交易请求
                        let txRequest = {
                            to: this.addressTo,
                            from: this.addressFrom,
                            nonce: Number(this.nonce),
                            data: '',
                            value: ethers.BigNumber.from(this.amount),
                            gasLimit: ethers.BigNumber.from(this.gasLimit),
                            gasPrice: ethers.BigNumber.from(this.gasPrice),
                            chainId: 5,
                        }

                        // 2. 交易请求数据加签
                        let wallet = new ethers.Wallet(ethers.utils.arrayify('0x' + this.privateKey))
                        let rawTransaction = await wallet.signTransaction(txRequest)

                        try {
                            const response = await axios.post('/api/wallets/sendRawTransaction', {
                                rawTransaction: rawTransaction,
                            })
                            let data = response.data

                            // jsonrpc api是否报错
                            if (data.hasOwnProperty('error')) {
                                this.msg = data.error.code + ' ' + data.error.message
                                return
                            }

                            this.msg = 'send transaction successful.'
                            this.tx = data.result
                            this.txURL = 'https://goerli.etherscan.io/tx/' + this.tx
                        } catch (error) {
                            this.msg = error
                        }
                    },
                }
            }).mount('#app')
        </script>
    </body>
</html>
