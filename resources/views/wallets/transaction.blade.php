<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Transaction</title>

        <script src="https://lf6-cdn-tos.bytecdntp.com/cdn/expire-1-M/vue/3.2.31/vue.global.min.js" type="application/javascript"></script>
        <script src="https://lf26-cdn-tos.bytecdntp.com/cdn/expire-1-M/ethers/5.5.4/ethers.umd.min.js" type="application/javascript"></script>
        <script src="https://lf9-cdn-tos.bytecdntp.com/cdn/expire-1-M/axios/0.26.0/axios.min.js" type="application/javascript"></script>

        <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/element-plus/dist/index.css" />
        <script src="//cdn.jsdelivr.net/npm/element-plus"></script>
        <link rel="stylesheet" href="/css/common.css" />
    </head>
    <body class="antialiased">
        <div id="app">
            @include('wallets.nav')

            <div id="content">
                <el-form label-width="auto">
                    <el-form-item label="Amount">
                        <el-input v-model="amount" placeholder="amount">
                            <template #append>
                                <span>wei</span>
                            </template>
                        </el-input>
                    </el-form-item>

                    <el-form-item label="Address To">
                        <el-input v-model="addressTo" placeholder="address to" />
                    </el-form-item>

                    <el-form-item label="Address From">
                        <el-input v-model="addressFrom" placeholder="address from" />
                    </el-form-item>

                    <el-form-item label="Private Key">
                        <el-input v-model="privateKey" placeholder="private key" />
                    </el-form-item>

                    <el-form-item label="Nonce">
                        <el-input v-model="nonce" placeholder="nonce" />
                    </el-form-item>

                    <el-form-item label="Gas Limit">
                        <el-input v-model="gasLimit" placeholder="gas limit" />
                    </el-form-item>

                    <el-form-item label="Gas Price">
                        <el-input v-model="gasPrice" placeholder="gas price" />
                    </el-form-item>

                    <el-form-item label=" ">
                        <el-button type="primary" @click="sendTransaction()">Send Transaction</button>
                    </el-form-item>
                </el-form>

                <table>
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
        </div>

        <script>
            const { createApp } = Vue

            createApp({
                data() {
                    return {
                        amount: 0,
                        addressTo: '',
                        addressFrom: '',
                        privateKey: '',
                        nonce: 0,
                        gasLimit: 1000000,
                        gasPrice: 33721725041,
                        tx: '',
                        txURL: '',
                        activeIndex: Vue.ref('4'),
                    }
                },
                methods: {
                    // ????????????
                    async sendTransaction() {
                        // ???????????????????????????????????????
                        if (!ethers.utils.isAddress(this.addressTo)) {
                            ElementPlus.ElMessage({
                                message: 'Address To format error.',
                                type: 'error',
                            })
                            return
                        }

                        // ???????????????????????????????????????
                        if (!ethers.utils.isAddress(this.addressFrom)) {
                            ElementPlus.ElMessage({
                                message: 'Address From format error.',
                                type: 'error',
                            })
                            return
                        }

                        // 1. ??????????????????
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

                        // 2. ????????????????????????
                        let wallet = new ethers.Wallet(ethers.utils.arrayify('0x' + this.privateKey))
                        let rawTransaction = await wallet.signTransaction(txRequest)

                        try {
                            const response = await axios.post('/api/wallets/sendRawTransaction', {
                                rawTransaction: rawTransaction,
                            })
                            let data = response.data

                            // jsonrpc api????????????
                            if (data.hasOwnProperty('error')) {
                                ElementPlus.ElMessage({
                                    message: data.error.code + ' ' + data.error.message,
                                    type: 'error',
                                })
                                return
                            }

                            this.tx = data.result
                            this.txURL = 'https://goerli.etherscan.io/tx/' + this.tx

                            ElementPlus.ElMessage({
                                message: 'Send transaction successful.',
                                type: 'success',
                            })
                        } catch (error) {
                            ElementPlus.ElMessage({
                                message: error,
                                type: 'error',
                            })
                        }
                    },
                }
            }).use(ElementPlus).mount('#app')
        </script>
    </body>
</html>
