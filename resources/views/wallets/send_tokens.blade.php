<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Send Tokens</title>

        <script src="https://lf6-cdn-tos.bytecdntp.com/cdn/expire-1-M/vue/3.2.31/vue.global.min.js" type="application/javascript"></script>
        <script src="https://lf26-cdn-tos.bytecdntp.com/cdn/expire-1-M/ethers/5.5.4/ethers.umd.min.js" type="application/javascript"></script>
        <script src="https://lf9-cdn-tos.bytecdntp.com/cdn/expire-1-M/axios/0.26.0/axios.min.js" type="application/javascript"></script>
        <script src="/js/abi.js"></script>

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
                                <span>tokens</span>
                            </template>
                        </el-input>
                    </el-form-item>

                    <el-form-item label="Contract Address">
                        <el-input v-model="contractAddress" placeholder="contract address" />
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
                        <el-button type="primary" @click="sendTokens()">Send Tokens</button>
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
                        contractAddress: '',
                        addressTo: '',
                        addressFrom: '',
                        privateKey: '',
                        nonce: 0,
                        gasLimit: 1000000,
                        gasPrice: 33721725041,
                        tx: '',
                        txURL: '',
                        activeIndex: Vue.ref('6'),
                    }
                },
                methods: {
                    // 发送tokens
                    async sendTokens() {
                        // 检查是否为智能合约地址
                        if (!ethers.utils.isAddress(this.contractAddress)) {
                            ElementPlus.ElMessage({
                                message: 'Contract Address format error.',
                                type: 'error',
                            })
                            return
                        }

                        // 检查转入地址是否为钱包地址
                        if (!ethers.utils.isAddress(this.addressTo)) {
                            ElementPlus.ElMessage({
                                message: 'Address To format error.',
                                type: 'error',
                            })
                            return
                        }

                        // 检查转出地址是否为钱包地址
                        if (!ethers.utils.isAddress(this.addressFrom)) {
                            ElementPlus.ElMessage({
                                message: 'Address From format error.',
                                type: 'error',
                            })
                            return
                        }

                        // 编码调用的abi函数及参数数据
                        var interface = new ethers.utils.Interface(abi_erc20)
                        var rawData = interface.encodeFunctionData('transfer',[
                            this.addressTo,
                            ethers.BigNumber.from(this.amount),
                        ])

                        // 1. 构造交易请求
                        let txRequest = {
                            to: this.contractAddress, // 合约地址
                            from: this.addressFrom,
                            nonce: Number(this.nonce),
                            data: rawData,
                            value: ethers.BigNumber.from(0),
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
                                ElementPlus.ElMessage({
                                    message: data.error.code + ' ' + data.error.message,
                                    type: 'error',
                                })
                                return
                            }

                            this.tx = data.result
                            this.txURL = 'https://goerli.etherscan.io/tx/' + this.tx

                            ElementPlus.ElMessage({
                                message: 'Send tokens successful.',
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
