<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Token</title>

        <script src="https://lf6-cdn-tos.bytecdntp.com/cdn/expire-1-M/vue/3.2.31/vue.global.min.js" type="application/javascript"></script>
        <script src="https://lf26-cdn-tos.bytecdntp.com/cdn/expire-1-M/ethers/5.5.4/ethers.umd.min.js" type="application/javascript"></script>
        <script src="https://lf9-cdn-tos.bytecdntp.com/cdn/expire-1-M/axios/0.26.0/axios.min.js" type="application/javascript"></script>
        <script src="/js/abi.js"></script>

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
                <el-form-item label="Contract Address">
                    <el-input v-model="contractAddress" placeholder="contract address" />
                </el-form-item>

                <el-form-item label="Wallet Address">
                    <el-input v-model="walletAddress" placeholder="wallet address" />
                </el-form-item>

                <el-form-item label=" ">
                    <el-button type="primary" @click="queryTokenBalance()">Query Token Balance</button>
                </el-form-item>
            </el-form>

            <table>
                <tr>
                    <th>Token Balance</th>
                    <td>@{{ tokenBalance }}</td>
                </tr>
            </table>
        </div>

        <script>
            const { createApp } = Vue

            createApp({
                data() {
                    return {
                        title: 'Token',
                        contractAddress: '',
                        walletAddress: '',
                        tokenBalance: 0,
                    }
                },
                methods: {
                    // 查询token余额
                    async queryTokenBalance() {
                        // 检查是否为智能合约地址
                        if (!ethers.utils.isAddress(this.contractAddress)) {
                            ElementPlus.ElMessage({
                                message: 'Contract Address format error.',
                                type: 'error',
                            })
                            return
                        }

                        // 检查是否为钱包地址
                        if (!ethers.utils.isAddress(this.walletAddress)) {
                            ElementPlus.ElMessage({
                                message: 'Wallet Address format error.',
                                type: 'error',
                            })
                            return
                        }

                        // 编码调用的abi函数及参数数据
                        var interface = new ethers.utils.Interface(abi_erc20)
                        var rawData = interface.encodeFunctionData('balanceOf',[
                            this.walletAddress,
                        ])

                        try {
                            const response = await axios.post('/api/wallets/tokenBalance', {
                                contractAddress: this.contractAddress,
                                rawData: rawData,
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

                            this.tokenBalance = ethers.BigNumber.from(data.result)

                            ElementPlus.ElMessage({
                                message: 'Query token balance successful.',
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
