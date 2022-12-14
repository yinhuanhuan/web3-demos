<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Token Balance</title>

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
        </div>

        <script>
            const { createApp } = Vue

            createApp({
                data() {
                    return {
                        contractAddress: '',
                        walletAddress: '',
                        tokenBalance: 0,
                        activeIndex: Vue.ref('5'),
                    }
                },
                methods: {
                    // ??????token??????
                    async queryTokenBalance() {
                        // ?????????????????????????????????
                        if (!ethers.utils.isAddress(this.contractAddress)) {
                            ElementPlus.ElMessage({
                                message: 'Contract Address format error.',
                                type: 'error',
                            })
                            return
                        }

                        // ???????????????????????????
                        if (!ethers.utils.isAddress(this.walletAddress)) {
                            ElementPlus.ElMessage({
                                message: 'Wallet Address format error.',
                                type: 'error',
                            })
                            return
                        }

                        // ???????????????abi?????????????????????
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

                            // jsonrpc api????????????
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
