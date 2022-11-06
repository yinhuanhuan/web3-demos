<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Address Balance</title>

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
                    <el-form-item label="Address">
                        <el-input v-model="address" placeholder="address" />
                    </el-form-item>

                    <el-form-item label=" ">
                        <el-button type="primary" @click="queryBalance()">Query Balance</button>
                    </el-form-item>
                </el-form>

                <table>
                    <tr>
                        <th>Balance</th>
                        <td>@{{ balance }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <script>
            const { createApp } = Vue

            createApp({
                data() {
                    return {
                        address: "",
                        balance: 0,
                        activeIndex: Vue.ref('3'),
                    }
                },
                methods: {
                    // 查询钱包余额
                    async queryBalance() {
                        // 检查是否为钱包地址
                        if (!ethers.utils.isAddress(this.address)) {
                            ElementPlus.ElMessage({
                                message: 'Address format error.',
                                type: 'error',
                            })
                            return
                        }

                        try {
                            const response = await axios.post('/api/wallets/balance', {
                                address: this.address,
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

                            this.balance = ethers.utils.formatUnits(ethers.BigNumber.from(data.result))

                            ElementPlus.ElMessage({
                                message: 'Query balance successful.',
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
