/**
 * Created by MF839 on 16/5/6.
 */
var myChart = echarts.init(document.getElementById('main'));
option = {

    series : [

        {
            name:'用户分布',
            type:'pie',
            center : ['50%', '50%'],
            radius : [40, 60],
            data:[
                {
                    value:335,
                    name:'12.77%',
                    itemStyle : {
                        normal : {
                            color : '#ff6e52',
                            label : {
                                textStyle : {
                                    color : '#666',
                                }
                            },
                            labelLine : {
                                lineStyle : {
                                    color : '#999',

                                }
                            }
                        }
                    }
                },
                {
                    value:600,
                    name:'8.04%',
                    itemStyle : {
                        normal : {
                            color : '#ffbe57',
                            label : {
                                textStyle : {
                                    color : '#666',
                                }
                            },
                            labelLine : {
                                lineStyle : {
                                    color : '#999',

                                }
                            }
                        }
                    }
                },
                {
                    value:600,
                    name:'7.78%',
                    itemStyle : {
                        normal : {
                            color : '#ffe000',
                            label : {
                                textStyle : {
                                    color : '#666',
                                }
                            },
                            labelLine : {
                                lineStyle : {
                                    color : '#999',

                                }
                            }
                        }
                    }
                },
                {
                    value:440,
                    name:'6.87%',
                    itemStyle : {
                        normal : {
                            color : '#cb7bff',
                            label : {
                                textStyle : {
                                    color : '#666',
                                }
                            },
                            labelLine : {
                                lineStyle : {
                                    color : '#999',

                                }
                            }
                        }
                    }
                },
                {
                    value:300,
                    name:'5.86%',
                    itemStyle : {
                        normal : {
                            color : '#8abff9',
                            label : {
                                textStyle : {
                                    color : '#666',
                                }
                            },
                            labelLine : {
                                lineStyle : {
                                    color : '#999',

                                }
                            }
                        }
                    }
                },
                {
                    value:1000,
                    name:'58.68%',
                    itemStyle : {
                        normal : {
                            color : '#66ccf5',
                            label : {
                                textStyle : {
                                    color : '#666',
                                }
                            },
                            labelLine : {
                                lineStyle : {
                                    color : '#999',

                                }
                            }
                        }
                    }
                }



            ]
        }

    ]
};
myChart.setOption(option);