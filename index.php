<script src="https://js.stripe.com/v3/"></script>
<div class="talk_area">
    <?php if (!empty($talkroom_data['message'])): ?>
        <div class="breadcrumb2"><a href="../talkrooms">トークルーム一覧</a> >
            <!-- 必ず相手を表示 -->
            <?php if ($usinfo['id'] == $talkroom_data['sender_id']): ?>
                <a
                    href="../user/<?php echo $talkroom_data['receiver_id']; ?>"><?php echo htmlspecialchars($receiver_info['name']); ?></a>
            <?php else: ?>
                <a
                    href="../user/<?php echo $talkroom_data['sender_id']; ?>"><?php echo htmlspecialchars($talkroom_data['sender_name']); ?></a>
            <?php endif; ?>
        </div>
        <div id="talk_area">
            <?php foreach ($talk_data as $value): ?>
                <?php if ($value['position'] == 'right'): ?>
                    <div class="talk_area_inner">
                        <div class="message_right">
                            <div class="chatting">
                                <div class="says_right">
                                    <p class="show_message"><?php echo htmlspecialchars($value['message']); ?></p>
                                </div>
                                <div class="talkroom_post_time"><?php echo $value['post_time']; ?></div>
                            </div>
                        </div>
                    </div>
                <?php elseif ($value['position'] == 'left'): ?>
                    <div class="talk_area_inner">
                        <div class="message_left">
                            <div class="faceicon">
                                <img src="../images/icon/<?php echo $value['receiver_id']; ?>/<?php echo $value['icon_name']; ?>">
                            </div>
                            <div class="chatting">
                                <div class="says_left">
                                    <p class="show_message"><?php echo htmlspecialchars($value['message']); ?></p>
                                </div>
                                <div class="talkroom_post_time"><?php echo $value['post_time']; ?></div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <?php
                    $total_price = $value['sender_id'] * $value['position'] + $value['post_time'];
                    $total_price_non_tax = $total_price; //合計金額(手数料なし)
                    $total_price_tax = $total_price * 0.15;
                    $total_price_tax = floor($total_price_tax);//(手数料)
                    $total_price = $total_price_non_tax + $total_price_tax;
                    $total_price = floor($total_price);//合計金額(手数料込み)
                    ?>

                    <div class="reserve_comp_area">
                        <div class="reserve_comp_area_inner">
                            <div class="reserve_comp_title">ご予約ありがとうございます。</div>
                            <div class="reserve_comp_item_wrap_first">
                                <div class="reserve_comp_item_left">実行日</div>
                                <div class="reserve_comp_item_right"><?php echo $value['login_user_id']; ?></div>
                            </div>
                            <div class="reserve_comp_item_wrap">
                                <div class="reserve_comp_item_left">料金</div>
                                <div class="reserve_comp_item_right"><?php echo $value['sender_id']; ?>円 /
                                    1<?php echo $value['receiver_id']; ?></div>
                            </div>
                            <div class="reserve_comp_item_wrap">
                                <div class="reserve_comp_item_left">決済手段</div>
                                <div class="reserve_comp_item_right"><?php echo $value['message']; ?></div>
                            </div>
                            <div class="reserve_comp_item_wrap">
                                <div class="reserve_comp_item_left">利用時間</div>
                                <div class="reserve_comp_item_right">
                                    <?php echo $value['position']; ?>             <?php echo $value['receiver_id']; ?>
                                </div>
                            </div>
                            <div class="reserve_comp_item_wrap">
                                <div class="reserve_comp_item_left">経費(交通費等)</div>
                                <div class="reserve_comp_item_right"><?php echo $value['post_time']; ?>円</div>
                            </div>
                            <div class="reserve_comp_item_wrap_last">
                                <div class="reserve_comp_item_left">合計金額</div>
                                <div class="reserve_comp_item_right"><?php echo $total_price; ?>円<div><span
                                            class="total_price_non_tax">(ご利用代金:<?php echo $total_price_non_tax; ?>円、</span><span
                                            class="total_price_tax">手数料:<?php echo $total_price_tax; ?>円)</span></div>
                                </div>
                            </div>
                            <?php if ($value['message'] == '銀行振込'): ?>
                                <div class="after_transfer_wrap">
                                    <div class="after_transfer_title">今後の流れ</div>
                                    <div class="after_transfer_text">
                                        <p class="bottom_space1">
                                            <?php echo htmlspecialchars($talkroom_data['sender_name']); ?>様が下記の口座に、合計金額
                                            <?php echo $total_price; ?>円をお振込みください。<br>※お振込み手数料は、<?php echo htmlspecialchars($talkroom_data['sender_name']); ?>様のご負担となります。
                                        </p>
                                        <ul class="bottom_space1">
                                            <li>銀行名：みずほ銀行</li>
                                            <li>店名(店番号)：北小金支店(333)</li>
                                            <li>口座種類：普通預金</li>
                                            <li>口座番号：1111111</li>
                                            <li>口座名義：タカダヒロシ</li>
                                        </ul>
                                        <p>運営がお振込みを確認しましたら、<?php echo htmlspecialchars($receiver_info['name']); ?>様にメールでご連絡いたしますので、その後、決定した日時に会う(オンラインの場合、通話をする)ようにしてください。
                                        </p>
                                        <p class="bottom_space1">
                                            ※<?php echo htmlspecialchars($receiver_info['name']); ?>様は、お手数ですが、運営から確認の連絡を受けましたら、ここのメッセージにて、<?php echo htmlspecialchars($talkroom_data['sender_name']); ?>様にお伝えいただきますようお願いいたします。
                                        </p>
                                        <p class="bottom_space1">もし、実行日までに確認が取れなかった場合は、実行日を変更するようにしてください。不正防止のため、確認前に会うことは禁止しております。
                                            ご理解・ご協力の程、よろしくお願いいたします。</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div><!-- id = talk_area終了 -->
    <?php else: ?><!-- 初めてメッセージ送信 -->
        <div id="talk_area">
            <div id="initial_screen" class="initial_screen" data-message="non">
                <div class="initial_screen_inner">
                    <div class="initial_screen_message">
                        <?php echo htmlspecialchars($receiver_info['name']); ?>さんへメッセージを送信します。
                    </div>
                    <div class="initial_screen_message2">下記フォームにメッセージを入力して、送信ボタンを押してください。</div>
                    <div class="request_caution_wrap">
                        <div class="request_caution">注意事項</div>
                        <ul class="request_caution_list">
                            <li>ひまっちでは、性的行為や出会いを目的とした依頼を禁止しております。発覚した場合は、ペナルティが課せられますので、ご注意ください。</li>
                            <li>キャンセルすることになった場合は、なるべく早くお相手の方へお知らせいただけますようお願いいたします。</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div><!-- talk_area終了 -->
<div class="scrollposition" id="scroll"></div>
<script>
    //var scroll_potision = $(".scrollposition").offset().top; /* - 400; ここの数値 = 最上部からの距離 */
    //$('html, body').animate({scrollTop:scroll_potision}, 0, 'swing');
</script>
<div class="outer">
    <div class="bottom_fixed" data-sender_id="<?php echo $talkroom_data['sender_id']; ?>"
        data-receiver_id="<?php echo $talkroom_data['receiver_id']; ?>"
        data-talkroom_id="<?php echo $talkroom_data['id']; ?>" data-pay_per="<?php echo $pay_per; ?>">
        <?php if ($talkroom_data['sender_id'] == $usinfo['id']): ?><!--ログインユーザーが依頼者の場合、予約ボタンあり-->
            <div class="reserve_button" id="open_modal">
                <span class="reserve_text pc_only">予約</span>
            </div>
            <div class="flex_textarea">
                <div class="flex_textarea_dummy" aria-hidden="true"></div>
                <textarea style="overflow:hidden;" id="message_textarea" class="flex_textarea_talkroom"></textarea>
            </div>
        <?php else: ?><!--ログインユーザーが受け手の場合、予約ボタン無し-->
            <div class="flex_textarea">
                <div class="flex_textarea_dummy" aria-hidden="true"></div>
                <textarea style="overflow:hidden;" id="message_textarea"
                    class="flex_textarea_talkroom_no_reserve"></textarea>
            </div>
        <?php endif; ?>
        <div class="send_button_talkroom">
            <input type="button" class="send-btn" id="msg-send-btn" value="送信" />
        </div>
    </div>
</div>
<?php if ($talkroom_data['sender_id'] == $usinfo['id']): ?>
    <script type="text/javascript">
        function flexTextarea(el) {
            const dummy = el.querySelector('.flex_textarea_dummy')
            el.querySelector('.flex_textarea_talkroom').addEventListener('input', e => {
                dummy.textContent = e.target.value + '\u200b'
            })
        }

        document.querySelectorAll('.flex_textarea').forEach(flexTextarea)
    </script>
<?php else: ?>
    <script type="text/javascript">
        function flexTextarea(el) {
            const dummy = el.querySelector('.flex_textarea_dummy')
            el.querySelector('.flex_textarea_talkroom_no_reserve').addEventListener('input', e => {
                dummy.textContent = e.target.value + '\u200b'
            })
        }

        document.querySelectorAll('.flex_textarea').forEach(flexTextarea)
    </script>
<?php endif; ?>

<!-- 予約モーダルエリア -->
<section id="reserve_modal_area" class="reserve_modal_area">
    <div id="reserve_modal_bg" class="reserve_modal_bg"></div>
    <div class="reserve_modal_wrapper">
        <form id="reserve_form">
            <div class="reserve" id="reserve">
                <div class="reserve_modal_title_wrap" id="reserve_modal_title_wrap">
                    <div id="reserve_modal_title" class="reserve_modal_title">予約画面</div>
                </div>
                <div class="receiver_info">
                    <div class="receiver_icon_area"><img class="receiver_icon"
                            src="../images/icon/<?php echo $receiver_info['id']; ?>/<?php echo $receiver_info['icon_name']; ?>">
                    </div>
                    <div class="receiver_name"><?php echo htmlspecialchars($receiver_info['name']); ?></div>
                    <div class="receiver_price">
                        <span class="" id="receiver_price_number"><?php
                        if ($receiver_info['pay_per'] > 0) {
                            $minute_price = $receiver_info['price'] / 60;
                            echo html_escape($minute_price);
                        } else {
                            echo html_escape($receiver_info['price']);
                        } ?></span>円 / 1<?php echo $pay_per; ?>
                    </div>
                </div>
                <div class="reserve_form_inputs">
                    <div class="reserve_item_wrap" id="reserve_item_wrap">
                        <div class="reserve_item">実行日</div>
                        <div class="reserve_form_area">
                            <div id="execution_date" class="execution_date"><input type="text" id="calender"
                                    class="calendar" name="name" readonly="readonly"></div>
                            <script>
                                $(function () {
                                    // jqueryで追加要素でも動作するように$(document)でdatepickerを設定。
                                    $(document).on('click', '.calendar', function () {
                                        //クリックのたびにdatepickerが登録されてしまうのでhasDatepickerがないときだけ実行
                                        if (!$(this).hasClass('hasDatepicker')) {
                                            $(this).datepicker({
                                                showOn: 'focus',
                                                minDate: 0,
                                                container: '#reserve_modal_area.reserve_modal_wrapper'
                                            }).focus();
                                        }
                                        const inputPosition = document.querySelector('#calender').getBoundingClientRect();
                                        const calendarPosition = document.querySelector('#ui-datepicker-div').getBoundingClientRect();
                                        // inputのポジションの±50px以内にカレンダーがない場合、absoluteに変更
                                        if (inputPosition.bottom + 50 < calendarPosition.top
                                            || inputPosition.top - 50 > calendarPosition.bottom) {
                                            $("#ui-datepicker-div").css("position", "absolute");
                                        }
                                    });
                                    $.datepicker.setDefaults($.datepicker.regional["ja"]);
                                });
                            </script>
                        </div>
                    </div>
                    <div class="reserve_item_wrap2" id="reserve_item_wrap2">
                        <div class="reserve_item_payment_method">決済手段</div>
                        <div id="payment_method_area" class="payment_method_area">
                            <select name="payment_method" id="payment_method">
                                <option value="credit">クレジットカード決済</option>
                                <option value="bank">銀行振込</option>
                            </select>
                        </div>
                    </div>
                    <div class="reserve_item_wrap3" id="reserve_item_wrap3">
                        <div class="reserve_item">利用時間</div>
                        <div id="usage_time_area" class="reserve_form_area5">
                            <input type="number" step="0.01" max="300" min="0.01" class="reserve_form2" id="usage_time"
                                name="usage_time"><span
                                class="pc_only"><?php if ($receiver_info['pay_per'] > 0): ?>分<?php else: ?>時間<?php endif; ?></span>
                        </div>
                        <div class="reserve_unit sp_only" id="reserve_unit">
                            <?php if ($receiver_info['pay_per'] > 0): ?>分<?php else: ?>時間<?php endif; ?>
                        </div>
                    </div>
                    <div class="reserve_item_wrap4" id="reserve_item_wrap4">
                        <div class="reserve_item">経費<span class="expenses">(交通費等)</span></div>
                        <div id="expenses_area" class="reserve_form_area6">
                            <input type="number" step="10" max="100000" min="0" class="reserve_form2" id="expenses"
                                name="expenses"><span class="pc_only">円</span>
                        </div>
                        <div class="reserve_unit2 sp_only" id="reserve_unit2">円</div>
                    </div>
                    <div class="total_price">
                        <div id="total_price">合計金額(手数料抜き)：<span id="total_price_number"
                                class="total_price_number">0</span>円<span
                                class="total_price_caution">※上記金額に15%の手数料がかかります。</span></div>
                    </div>
                    <div id="card-element"><!-- Stripe Elements will create an input field here --></div>
                    <div class="reserve_modal_button" id="reserve_modal_button">
                        <div class="reserve_confirm" id="confirm_button">確認画面へ</div>
                    </div>
                </div>
            </div>
            <div id="close_modal" class="close_modal">×</div>
        </form>
        <input type="hidden" id="execution_date_hidden">
        <input type="hidden" id="payment_method_hidden">
        <input type="hidden" id="usage_time_hidden">
        <input type="hidden" id="expenses_hidden">
        <input type="hidden" id="total_price_non_tax_hidden">
    </div>
</section>

<script>
    $(function () {
        $('#open_modal').click(function () {
            //$(document).on('click', '#open_modal', function(){  
            //メッセージなしで予約は不可
            var message_check = $('.initial_screen').data('message');
            if (message_check == 'non') {
                alert('予約する前に、メッセージをお送りください。');
                return false;
            }

            $('#reserve_modal_area').fadeIn();
            //$('body').css('overflow', 'hidden'); つけると予約完了後動かなくなる
            //時給(分給)を取得
            var receiver_price_number = document.getElementById('receiver_price_number');
            var receiver_price = Number(receiver_price_number.innerText);
            //console.log(receiver_price);

            //usage_time.oninput = function() {
            $(document).on('input', '#usage_time', function (e) {
                var usage_time_calc = usage_time.value;
                var usage_time_calc = Number(usage_time_calc)
                var total_price = receiver_price * usage_time_calc;

                var expenses_calc = expenses.value;
                if (0 < expenses_calc) {
                    var expenses_calc = Number(expenses_calc);
                    var total_price = total_price + expenses_calc;
                } else {
                    var expenses_calc = 0;
                }
                //console.log(expenses_calc);
                var total_price = Math.floor(total_price);
                total_price_number.innerText = total_price;
            });

            //expenses.oninput = function() {
            $(document).on('input', '#expenses', function (e) {
                var expenses_calc = expenses.value;
                var expenses_calc = Number(expenses_calc)

                var usage_time_calc = usage_time.value;
                if (0 < usage_time_calc) {
                    var usage_time_calc = Number(usage_time_calc);
                    var total_price = receiver_price * usage_time_calc;
                } else {
                    //利用時間0はエラーにする
                    var usage_time_calc = 0;
                    var total_price = 0;
                }
                var total_price = total_price + expenses_calc;

                var total_price = Math.floor(total_price);
                total_price_number.innerText = total_price;
            });
        });

        //$(document).on('click', '#close_modal , #reserve_modal_bg', function(){
        $('#close_modal , #reserve_modal_bg').click(function () {
            var close = window.confirm("予約画面を閉じます。\n入力した値は破棄されますが、よろしいですか？");
            if (close == false) {
                return false;
            } else {
                $('#reserve_modal_area').fadeOut();
                $('body').css('overflow', 'auto');
                /*
                window.setTimeout(function(){
                    alert('閉じてから1秒経過しました');
                }, 1000);*/
                //フォームの値をクリア
                var confirm_button_now = document.getElementById('confirm_button');
                var confirm_button_now = confirm_button_now.innerText;
                if (confirm_button_now == '確認画面へ') {
                    //入力画面で閉じる
                    //var form = document.getElementById("reserve_form");
                    //total_price_number.innerText = 0;
                    //form.reset();
                    document.getElementById('calender').value = "";
                    document.getElementById('usage_time').value = "";
                    document.getElementById('expenses').value = "";
                    total_price_number.innerText = 0;
                } else {
                    window.setTimeout(function () {
                        //確認画面で閉じる
                        var origin_item1 = document.getElementById("reserve");
                        var origin_item2 = document.getElementById("reserve_modal_title_wrap");
                        var origin_item3 = document.getElementById("reserve_modal_title");
                        var origin_item4 = document.getElementById("reserve_item_wrap");
                        var origin_item5 = document.getElementById("reserve_item_wrap2");
                        var origin_item6 = document.getElementById("reserve_item_wrap3");
                        var origin_item7 = document.getElementById("usage_time_area");
                        var origin_item8 = document.getElementById("reserve_item_wrap4");
                        var origin_item9 = document.getElementById("expenses_area");
                        var origin_item10 = document.getElementById("confirm_button");
                        var origin_item11 = document.getElementById("reserve_modal_button");

                        origin_item1.className = "reserve";
                        origin_item2.className = "reserve_modal_title_wrap";
                        origin_item3.className = "reserve_modal_title";
                        origin_item4.className = "reserve_item_wrap";
                        origin_item5.className = "reserve_item_wrap2";
                        origin_item6.className = "reserve_item_wrap3";
                        origin_item7.className = "reserve_form_area5";
                        origin_item8.className = "reserve_item_wrap4";
                        origin_item9.className = "reserve_form_area6";
                        origin_item10.className = "reserve_confirm";
                        origin_item11.className = "reserve_modal_button";

                        document.getElementById('reserve_modal_title').innerHTML = '予約画面';
                        document.getElementById('execution_date').innerHTML = '<input type="text" id="calender" class="calendar" name="name" readonly="readonly" value="">';
                        //document.getElementById('payment_method_area').innerHTML = '<select name="payment_method" id="payment_method"><option value="credit">クレジットカード決済</option><option value="bank">銀行振込</option></select>';
                        var pay_per = $('.bottom_fixed').data('pay_per');
                        if (pay_per == '時間') {
                            document.getElementById('usage_time_area').innerHTML = '<input type="number" step="0.01" max="300" min="0.01" class="reserve_form2" id="usage_time" name="usage_time" value=""><span class="pc_only">時間</span>';
                        } else {
                            document.getElementById('usage_time_area').innerHTML = '<input type="number" step="0.01" max="300" min="0.01" class="reserve_form2" id="usage_time" name="usage_time" value=""><span class="pc_only">分</span>';
                        }
                        document.getElementById('expenses_area').innerHTML = '<input type="number" step="10" max="100000" min="0" class="reserve_form2" id="expenses" name="expenses" value=""><span class="pc_only">円</span>';
                        document.getElementById('reserve_unit').innerHTML = pay_per;
                        document.getElementById('reserve_unit2').innerHTML = '円';
                        document.getElementById('total_price').innerHTML = '合計金額(手数料抜き)：<span id="total_price_number" class="total_price_number">0</span>円<span class="total_price_caution">※上記金額に15%の手数料がかかります。</span>';
                        document.getElementById('confirm_button').innerHTML = '確認画面へ';
                        if (document.getElementById('return_input') != null) {
                            document.getElementById('return_input').remove();
                        }
                        //alert('閉じてから1秒経過しました');

                        document.getElementById('payment_method_area').innerHTML = '<select name="payment_method" id="payment_method"><option value="credit">クレジットカード決済</option><option value="bank">銀行振込</option></select>';
                    }, 1000);
                }
                //document.getElementById('payment_method_area').innerHTML = '<select name="payment_method" id="payment_method"><option value="credit">クレジットカード決済</option><option value="bank">銀行振込</option></select>';
                //location.reload();
            }
        });
        //Stripe決済部分
        const stripe = Stripe(
            'pk_test_51OxMuA2NouBXQI50Me70dbLZ9yjHd63umuaXEOxQUIlyl5vavfhDXA2otMSVtvRvUB7KRvm0QjeGAS8NCuUfHbpU00jEfWQedQ'
        );
        const elements = stripe.elements();
        const style = {
            base: {
                color: '#32325d',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '14px',
                lineHeight: '24px',
                padding: '10px',
                '::placeholder': {
                    color: '#aab7c4',
                    fontSize: '14px'
                },
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        };

        // カード番号
        const cardNumber = elements.create('cardNumber', {
            style: style,
            placeholder: 'カード番号(16桁)' // カスタムプレースホルダー
        });
        // 有効期限
        const cardExpiry = elements.create('cardExpiry', {
            style: style,
            placeholder: '有効期限(MM/YY)' // カスタムプレースホルダー
        });
        // セキュリティコード
        const cardCvc = elements.create('cardCvc', {
            style: style,
            placeholder: 'セキュリティコード(3桁)' // カスタムプレースホルダー
        });

        //確認画面へをクリック
        async function fetchData() {
            const {
                paymentMethod,
                error
            } = await stripe.createPaymentMethod({
                type: 'card',
                card: cardNumber,
            });
            if (error) {
                alert("カード情報が正しくありません。");
                //return 0;
                return false;
            } else {
                alert("お支払いが完了しました。" + paymentMethod.id);

                return paymentMethod.id;
            }
        }

        $('#confirm_button').click(async function () {
            var confirm_button_now = document.getElementById('confirm_button');
            var confirm_button_now = confirm_button_now.innerText;
            //確認画面だった場合、予約完了処理へ

            if (confirm_button_now == '予約を確定する') {
                var payment_method_now = document.getElementById('payment_method_area');
                var payment_method_now = payment_method_now.innerText;
                //console.log(payment_method_now);

                let p_id;
                if (payment_method_now == '銀行振込') {
                    p_id = 'bank_transfer';
                } else {
                    alert("Start");
                
                    p_id = await fetchData();

                    // fetchData が失敗（false を返す）した場合は処理を中断
                    if (!p_id) {
                        alert("処理を中断します。");
                        return;
                    }

                    alert("End");
                }

                //Thankyou画面で表示する値を取得
                var executionDate = document.getElementById('execution_date');
                var execution_date = executionDate.textContent;
                execution_date = execution_date.replace(/^(\d+)\/(\d+)\/(\d+)$/, function () {
                    var month, day;
                    month = arguments[2];
                    day = arguments[3];
                    month = month ? month.replace(/^0/, '') : '';
                    day = day ? day.replace(/^0/, '') : '';
                    return arguments[1] + "年" + month + "月" + day + "日";
                });
                //console.log(execution_date);

                var receiverPriceNumber = document.getElementById('receiver_price_number');
                var receiver_price = Number(receiverPriceNumber.innerText);
                receiver_price.toLocaleString();

                var pay_per = $('.bottom_fixed').data('pay_per');
                //console.log(pay_per);
                //console.log(payment_method_now);

                var usageTime = document.getElementById('usage_time');
                var usage_time = Number(usageTime.innerText);
                //console.log(usage_time);

                var expenses = document.getElementById('expenses');
                var expenses = Number(expenses.innerText);
                expenses.toLocaleString();

                //$('#reserve_modal_area').fadeOut("fast");
                //$('#reserve_modal_area').fadeOut(100);
                $('#reserve_modal_area').fadeOut();

                window.setTimeout(function () {
                    //フォームの値をクリア ※初期状態に戻している
                    var origin_item1 = document.getElementById("reserve");
                    var origin_item2 = document.getElementById("reserve_modal_title_wrap");
                    var origin_item3 = document.getElementById("reserve_modal_title");
                    var origin_item4 = document.getElementById("reserve_item_wrap");
                    var origin_item5 = document.getElementById("reserve_item_wrap2");
                    var origin_item6 = document.getElementById("reserve_item_wrap3");
                    var origin_item7 = document.getElementById("usage_time_area");
                    var origin_item8 = document.getElementById("reserve_item_wrap4");
                    var origin_item9 = document.getElementById("expenses_area");
                    var origin_item10 = document.getElementById("confirm_button");
                    var origin_item11 = document.getElementById("reserve_modal_button");

                    origin_item1.className = "reserve";
                    origin_item2.className = "reserve_modal_title_wrap";
                    origin_item3.className = "reserve_modal_title";
                    origin_item4.className = "reserve_item_wrap";
                    origin_item5.className = "reserve_item_wrap2";
                    origin_item6.className = "reserve_item_wrap3";
                    origin_item7.className = "reserve_form_area5";
                    origin_item8.className = "reserve_item_wrap4";
                    origin_item9.className = "reserve_form_area6";
                    origin_item10.className = "reserve_confirm";
                    origin_item11.className = "reserve_modal_button";

                    document.getElementById('reserve_modal_title').innerHTML = '予約画面';
                    document.getElementById('execution_date').innerHTML = '<input type="text" id="calender" class="calendar" name="name" readonly="readonly">';
                    document.getElementById('payment_method_area').innerHTML = '<select name="payment_method" id="payment_method"><option value="credit">クレジットカード決済</option><option value="bank">銀行振込</option></select>';
                    if (pay_per == '時間') {
                        document.getElementById('usage_time_area').innerHTML = '<input type="number" step="0.01" max="300" min="0.01" class="reserve_form2" id="usage_time" name="usage_time"><span class="pc_only">時間</span>';
                    } else {
                        document.getElementById('usage_time_area').innerHTML = '<input type="number" step="0.01" max="300" min="0.01" class="reserve_form2" id="usage_time" name="usage_time"><span class="pc_only">分</span>';
                    }
                    document.getElementById('expenses_area').innerHTML = '<input type="number" step="10" max="100000" min="0" class="reserve_form2" id="expenses" name="expenses"><span class="pc_only">円</span>';
                    document.getElementById('reserve_unit').innerHTML = pay_per;
                    document.getElementById('reserve_unit2').innerHTML = '円';
                    document.getElementById('total_price').innerHTML = '合計金額(手数料抜き)：<span id="total_price_number" class="total_price_number">0</span>円<span class="total_price_caution">※上記金額に15%の手数料がかかります。</span>';
                    document.getElementById('confirm_button').innerHTML = '確認画面へ';
                    if (document.getElementById('return_input') != null) {
                        document.getElementById('return_input').remove();
                    }
                }, 1000);

                //予約データをDBに挿入
                var talkroomid = $('.bottom_fixed').data('talkroom_id');
                var senderid = $('.bottom_fixed').data('sender_id');
                var receiverid = $('.bottom_fixed').data('receiver_id');

                $.ajax({
                    url: 'https://himatch.jp/ajax/talkroom',
                    type: "post",
                    data: {
                        talkroomid: talkroomid,
                        senderid: senderid,
                        receiverid: receiverid,
                        reserve_check: 'yes',
                        execution_date: execution_date,
                        receiver_price: receiver_price,
                        pay_per: pay_per,
                        payment_method_now: payment_method_now,
                        usage_time: usage_time,
                        expenses: expenses,
                        p_id: p_id
                    },
                })
                    .done(function (data, textStatus, jqXHR) {
                        //console.log(data);
                        var data_json = JSON.parse(data);
                        //console.log(data_json);

                        const thank_you = document.querySelector('#talk_area');
                        if (data_json.payment_method_now == '銀行振込') {
                            var thank_you_message = '<div class="reserve_comp_area"><div class="reserve_comp_area_inner"><div class="reserve_comp_title">ご予約ありがとうございます。</div><div class="reserve_comp_item_wrap_first"><div class="reserve_comp_item_left">実行日</div><div class="reserve_comp_item_right">' + data_json.execution_date + '</div></div><div class="reserve_comp_item_wrap"><div class="reserve_comp_item_left">料金</div><div class="reserve_comp_item_right">' + data_json.receiver_price + '円 / 1' + data_json.pay_per + '</div></div><div class="reserve_comp_item_wrap"><div class="reserve_comp_item_left">決済手段</div><div class="reserve_comp_item_right">' + data_json.payment_method_now + '</div></div><div class="reserve_comp_item_wrap"><div class="reserve_comp_item_left">利用時間</div><div class="reserve_comp_item_right">' + data_json.usage_time + '時間</div></div><div class="reserve_comp_item_wrap"><div class="reserve_comp_item_left">経費(交通費等)</div><div class="reserve_comp_item_right">' + data_json.expenses + '円</div></div><div class="reserve_comp_item_wrap_last"><div class="reserve_comp_item_left">合計金額</div><div class="reserve_comp_item_right">' + data_json.total_price + '円<div><span class="total_price_non_tax">(ご利用代金:' + data_json.total_price_non_tax + '円、</span><span class="total_price_tax">手数料:' + data_json.total_price_tax + '円)</span></div></div></div><div class="after_transfer_wrap"><div class="after_transfer_title">今後の流れ</div><div class="after_transfer_text"><p class="bottom_space1">' + data_json.sender_name + '様が下記の口座に、合計金額 ' + data_json.total_price + '円をお振込みください。<br>※お振込み手数料は、' + data_json.sender_name + '様のご負担となります。</p><ul class="bottom_space1"><li>銀行名：みずほ銀行</li><li>店名(店番号)：北小金支店(333)</li><li>口座種類：普通預金</li><li>口座番号：1111111</li><li>口座名義：タカダヒロシ</li></ul><p>運営がお振込みを確認しましたら、' + data_json.receiver_name + '様にメールでご連絡いたしますので、その後、決定した日時に会う(オンラインの場合、通話をする)ようにしてください。</p><p class="bottom_space1">※' + data_json.receiver_name + '様は、お手数ですが、運営から確認の連絡を受けましたら、ここのメッセージにて、' + data_json.sender_name + '様にお伝えいただきますようお願いいたします。</p><p class="bottom_space1">もし、実行日までに確認が取れなかった場合は、実行日を変更するようにしてください。不正防止のため、確認前に会うことは禁止しております。ご理解・ご協力の程、よろしくお願いいたします。</p></div></div></div></div>';
                        } else {
                            var thank_you_message = '<div class="reserve_comp_area"><div class="reserve_comp_area_inner"><div class="reserve_comp_title">ご予約ありがとうございます。</div><div class="reserve_comp_item_wrap_first"><div class="reserve_comp_item_left">実行日</div><div class="reserve_comp_item_right">' + data_json.execution_date + '</div></div><div class="reserve_comp_item_wrap"><div class="reserve_comp_item_left">料金</div><div class="reserve_comp_item_right">' + data_json.receiver_price + '円 / 1' + data_json.pay_per + '</div></div><div class="reserve_comp_item_wrap"><div class="reserve_comp_item_left">決済手段</div><div class="reserve_comp_item_right">' + data_json.payment_method_now + '</div></div><div class="reserve_comp_item_wrap"><div class="reserve_comp_item_left">利用時間</div><div class="reserve_comp_item_right">' + data_json.usage_time + '時間</div></div><div class="reserve_comp_item_wrap"><div class="reserve_comp_item_left">経費(交通費等)</div><div class="reserve_comp_item_right">' + data_json.expenses + '円</div></div><div class="reserve_comp_item_wrap_last"><div class="reserve_comp_item_left">合計金額</div><div class="reserve_comp_item_right">' + data_json.total_price + '円<div><span class="total_price_non_tax">(ご利用代金:' + data_json.total_price_non_tax + '円、</span><span class="total_price_tax">手数料:' + data_json.total_price_tax + '円)</span></div></div></div></div></div>';
                        }
                        thank_you.insertAdjacentHTML('beforeend', thank_you_message);

                        var scroll_potision = $(".scrollposition").offset().top; /* - 400; ここの数値 = 最上部からの距離 */
                        $('html, body').animate({ scrollTop: scroll_potision }, 0, 'swing');
                    })
                    .fail(function () {
                        alert("予約情報を保存できませんでした。\nお手数ですが、再度ご予約をお願いいたします。");
                    });

                //レビューする権限を依頼者に付与
                var senderid = $('.bottom_fixed').data('sender_id');
                var receiverid = $('.bottom_fixed').data('receiver_id');

                $.ajax({
                    url: 'https://himatch.jp/ajax/review_authority',
                    type: "post",
                    data: {
                        senderid: senderid,
                        receiverid: receiverid
                    },
                })
                    .done(function (data, textStatus, jqXHR) {
                        //alert("レビュー成功しました。");
                    })
                    .fail(function () {
                        alert("レビューできるようになりませんでした。\nお手数ですが、管理者へお問い合わせください。");
                    });
            } else {
                //実行日 入力必須


                var execution_date = document.getElementById('calender').value;
                if (0 < execution_date.length) {
                } else {
                    alert('実行日を入力してください');
                    return false;
                }
                //console.log(calender);
                //2024/05/09 この形式でないとエラー ★追加する

                //時給か分給か
                var pay_per = $('.bottom_fixed').data('pay_per');

                //利用時間 入力必須
                var usage_time = document.getElementById('usage_time').value;
                if (0 < usage_time) {
                    var usage_time = Number(usage_time);
                } else {
                    alert('利用時間を入力してください');
                    return false;
                }
                document.getElementById('usage_time_area').innerHTML = '<span id="usage_time">' + usage_time + '</span>' + '<span class="pc_only">' + pay_per + '</span><span class="sp_only left_space2">' + pay_per + '</span>';
                document.getElementById('reserve_unit').innerHTML = '';
                document.getElementById('reserve_unit2').innerHTML = '';
                document.getElementById('reserve_modal_title').innerHTML = '確認画面';
                document.getElementById('execution_date').innerHTML = execution_date;

                //決済手段 取得
                /* $('#payment_method_area').addClass('payment_method'); あると右端に寄る */
                var selected_payment_method = document.getElementById('payment_method');
                //alert(selected_payment_method.value);


                var selected_payment_method = selected_payment_method.value;
                if (selected_payment_method == 'credit') {
                    // document.getElementById('payment_method_area').innerHTML = '<div class="credit_text">クレジットカード決済</div><div class="reserve_form_area2"><input type="text" class="reserve_form3" name="name" placeholder="カード番号(16桁)"></div><div class="reserve_form_area3"><input type="text" class="reserve_form3" name="name" placeholder="有効期限(MM/YY)"></div><div class="reserve_form_area4"><input type="text" class="reserve_form4" name="name" placeholder="セキュリティコード(3桁)"></div>';   
                    credit_text_html = `
                    <style>
                        .reserve_item_payment_method {
                            display: table-cell;
                            width: 150px;
                            color: #ff8b00;
                            vertical-align: top;
                            padding-top: 5px;
                        }
                        #card-number, #card-expiry, #card-cvc {
                            position: relative;
                            left: 10px;
                            padding-left: 1px;
                            border-bottom: 1px solid #ccc;
                            margin-left: 20px;
                            width: 200px;
                            height: 25px;
                        }
                        #card-number::before, #card-expiry::before, #card-cvc:before {
                            content: '';
                            display: inline-block;
                            width: 10px;
                            height: 10px;
                            background-color: #ff8b00;
                            border-radius: 50%;
                            position: absolute;
                            left: -15px;
                            top: 50%;
                            transform: translateY(-50%);
                        }
                    </style>
                    <div class="credit_text">クレジットカード決済<label class="use_previous_card"><input type="checkbox" id="use_previous_card">前回のカード情報を利用する</label></div>
                    <form id="payment-form">
                        <!-- カード番号 -->
                        <div id="card-number"></div>
                        <!-- 有効期限 -->
                        <div id="card-expiry"></div>
                        <!-- セキュリティコード -->
                        <div id="card-cvc"></div>
                        <!-- エラーメッセージ -->
                        <div id="card-errors" role="alert"></div>
                    </form>`;
                    document.getElementById('payment_method_area').innerHTML = credit_text_html;
                    // カード番号、有効期限、セキュリティコードをマウント
                    cardNumber.mount('#card-number');
                    cardExpiry.mount('#card-expiry');
                    cardCvc.mount('#card-cvc');

                    const form = document.getElementById('payment-form');

                    //NEW END
                } else {
                    document.getElementById('payment_method_area').innerHTML = "銀行振込";
                }

                var expenses = document.getElementById('expenses').value;
                if (0 < expenses) {
                    var expenses = Number(expenses);
                } else {
                    //経費(交通費)は無しでもok
                    var expenses = 0;
                }
                document.getElementById('expenses_area').innerHTML = '<span id="expenses">' + expenses + '</span><span class="pc_only">円</span><span class="sp_only left_space2">円</span>';

                var total_price = document.getElementById('total_price_number');
                var total_price = Number(total_price.innerText);
                var total_price_non_tax = total_price;
                var total_price_tax = total_price * 0.15;
                var total_price_tax = Math.floor(total_price_tax);
                var total_price = total_price_non_tax + total_price_tax;
                var total_price = Math.floor(total_price);

                document.getElementById('total_price').innerHTML = '合計金額：<span id="total_price_number" class="total_price_number">' + total_price + '</span>円<span class="total_price_caution">(ご利用代金:' + total_price_non_tax + '円、手数料:' + total_price_tax + '円)</span>';
                document.getElementById('confirm_button').innerHTML = '予約を確定する';

                //確認画面のデザイン変更
                let change_item1 = document.getElementById("reserve");
                let change_item2 = document.getElementById("reserve_modal_title_wrap");
                let change_item3 = document.getElementById("reserve_modal_title");
                let change_item4 = document.getElementById("reserve_item_wrap");
                let change_item5 = document.getElementById("reserve_item_wrap2");
                let change_item6 = document.getElementById("reserve_item_wrap3");
                let change_item7 = document.getElementById("usage_time_area");
                let change_item8 = document.getElementById("reserve_item_wrap4");
                let change_item9 = document.getElementById("expenses_area");
                let change_item10 = document.getElementById("confirm_button");
                let change_item11 = document.getElementById("reserve_modal_button");

                change_item1.className = "confirm";
                change_item2.className = "confirm_modal_title_wrap";
                change_item3.className = "confirm_modal_title";
                change_item4.className = "confirm_item_wrap";
                change_item5.className = "confirm_item_wrap2";
                change_item6.className = "confirm_item_wrap3";
                change_item7.className = "confirm_form_area";
                change_item8.className = "confirm_item_wrap4";
                change_item9.className = "confirm_form_area2";
                change_item10.className = "reserve_confirm2";
                change_item11.className = "reserve_modal_button2";

                // 値をhiddenに格納し、入力画面へ戻るで復活できるようにする
                document.getElementById('execution_date_hidden').value = execution_date;
                document.getElementById('payment_method_hidden').value = selected_payment_method;
                document.getElementById('usage_time_hidden').value = usage_time;
                document.getElementById('expenses_hidden').value = expenses;
                document.getElementById('total_price_non_tax_hidden').value = total_price_non_tax;

                //入力画面へ戻るボタンを表示(存在する場合はスキップ)
                const confirm_button = document.getElementById('confirm_button');
                if (document.getElementById('return_input') == null) {
                    confirm_button.insertAdjacentHTML('afterend', '<div class="return_input_wrap"><div id="return_input" class="return_input"><span class="pc_only">入力画面へ</span>戻る</div></div>');
                }
            }
        });

        //入力画面へ
        $(document).on('click', '#return_input', function () {
            var execution_date = document.getElementById('execution_date_hidden').value;
            var payment_method = document.getElementById('payment_method_hidden').value;
            var usage_time = document.getElementById('usage_time_hidden').value;
            var expenses = document.getElementById('expenses_hidden').value;
            var total_price_non_tax = document.getElementById('total_price_non_tax_hidden').value;

            var pay_per = $('.bottom_fixed').data('pay_per');

            let return_item1 = document.getElementById("reserve");
            let return_item2 = document.getElementById("reserve_modal_title_wrap");
            let return_item3 = document.getElementById("reserve_modal_title");
            let return_item4 = document.getElementById("reserve_item_wrap");
            let return_item5 = document.getElementById("reserve_item_wrap2");
            let return_item6 = document.getElementById("reserve_item_wrap3");
            let return_item7 = document.getElementById("usage_time_area");
            let return_item8 = document.getElementById("reserve_item_wrap4");
            let return_item9 = document.getElementById("expenses_area");
            let return_item10 = document.getElementById("confirm_button");
            let return_item11 = document.getElementById("reserve_modal_button");

            return_item1.className = "reserve";
            return_item2.className = "reserve_modal_title_wrap";
            return_item3.className = "reserve_modal_title";
            return_item4.className = "reserve_item_wrap";
            return_item5.className = "reserve_item_wrap2";
            return_item6.className = "reserve_item_wrap3";
            return_item7.className = "reserve_form_area5";
            return_item8.className = "reserve_item_wrap4";
            return_item9.className = "reserve_form_area6";
            return_item10.className = "reserve_confirm";
            return_item11.className = "reserve_modal_button";

            document.getElementById('reserve_modal_title').innerHTML = '予約画面';
            document.getElementById('execution_date').innerHTML = '<input type="text" id="calender" class="calendar" name="execution_date" readonly="readonly" value=' + execution_date + '>';
            if (payment_method == 'credit') {
                document.getElementById('payment_method_area').innerHTML = '<select name="payment_method" id="payment_method"><option value="credit">クレジットカード決済</option><option value="bank">銀行振込</option></select>';
            } else {
                document.getElementById('payment_method_area').innerHTML = '<select name="payment_method" id="payment_method"><option value="credit">クレジットカード決済</option><option value="bank" selected>銀行振込</option></select>';
            }
            document.getElementById('usage_time_area').innerHTML = '<input type="number" step="0.01" max="300" min="0.01" class="reserve_form2" id="usage_time" name="usage_time" value=' + usage_time + '><span class="pc_only">' + pay_per + '</span>';
            document.getElementById('expenses_area').innerHTML = '<input type="number" step="10" max="100000" min="0" class="reserve_form2" id="expenses" name="expenses" value=' + expenses + '><span class="pc_only">円</span>';
            document.getElementById('reserve_unit').innerHTML = pay_per;
            document.getElementById('reserve_unit2').innerHTML = '円';
            document.getElementById('total_price').innerHTML = '合計金額(手数料抜き)：<span id="total_price_number" class="total_price_number">' + total_price_non_tax + '</span>円<span class="total_price_caution">※上記金額に15%の手数料がかかります。</span>';
            document.getElementById('confirm_button').innerHTML = '確認画面へ';
            $(this).remove()
        });
    });
</script>
<!-- 予約モーダルエリアここまで -->