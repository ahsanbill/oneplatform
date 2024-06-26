
@php $hasActiveSub = $user->hasActivePaidSubscription() @endphp

<div class="mt-12 each-stage-det {{!isset($defaultTab) || $defaultTab == '' || $defaultTab == 'my-contacts' ? '' : 'instant_hide'}}" data-stage-ref="my-contacts">
    <div class="items-end m_btm_filters_outer md:items-center">
        <div class="flex items-center flex-1 m_btm_filter_search m_btm_filter_search_field">
            <i class="ml-2 text-gray-500 fa fa-search"></i>
            <div data-target="agent_contact_listing" class="flex-1 ml-2" contenteditable="true" placeholder="Search your contacts by name"></div>
        </div>
        <div @if(isset($userGroups)) style="opacity: 0;" @endif class="flex-1 m_btm_filter_drop">
            <select data-target="agent_contact_listing" class="m_btm_filter_dropdown">
                <option value="">Filter contacts</option>
                <option value="all">All</option>
                <option value="All Approved">All Approved</option>
                <option value="All Unapproved">All Upapproved</option>
                <option value="Main Network">My Main Network</option>
            </select>
        </div>
        @if(!isset($userGroups))
        <div class="flex-1 mt-10 smart_switch_outer switch_personal_chat md:mt-0 md:ml-auto">
            <div class="smart_switch_txt">Show Personal Chat</div>
            <label class="smart_switch">
                <input type="checkbox" />
                <span class="slider"></span>
            </label>
        </div>
        @endif
    </div>
    @if(isset($userGroups) && !$user->is_buyer_only)
    <div class="pro_note block">
        <ul>
            <li>Share your support URL with your fans, followers and supporters</li>
            <li>Your support URL: <a href="{{route('user.supporter.form', ['username' => Auth::user()->username])}}">{{route('user.supporter.form', ['username' => Auth::user()->username])}}</a></li>
        </ul>
    </div>
    @endif
    <div class="btn_list_outer">
        <div class="chat_filter_container">
            <div class="chat_filter_tab chat_filter_contacts">
            @if(isset($contacts) && count($contacts) > 0)
                @php $counter = 0 @endphp
                @foreach($contacts as $key => $contact)
                    @if(!$contact->contactUser || !$contact->agentUser)
                        @php continue @endphp
                    @else
                        @php $counter++ @endphp
                    @endif
                    @php
                        $contactUser = $contact->contactUser;
                        $agentUser = $contact->agentUser;
                        $isAgentUser = $user->isAgentOfContact($contact);
                        $partnerUser = $isAgentUser ? $contactUser : $agentUser;
                        $contactPDetails = $commonMethods->getUserRealDetails($partnerUser->id)
                    @endphp
                    <div data-partner="{{$partnerUser->id}}" data-approved="{{$contact->approved ? '1' : ''}}" data-form="my-contact-form_{{ $contact->id }}" class="clearfix agent_contact_listing music_btm_list no_sorting">
                        <div class="edit_elem_top">
                            <div class="m_btm_list_left">
                                @if($counter < 10)
                                <div class="music_btm_thumb">
                                    <img src="{{$contactPDetails['image']}}" alt="">
                                </div>
                                @else
                                <div data-image="{{$contactPDetails['image']}}" class="music_btm_thumb">
                                    <div class="music_bottom_load_thumb">Load Image</div>
                                </div>
                                @endif
                                <ul class="music_btm_img_det">
                                    <li>
                                        <a class="filter_search_target" href="">
                                            {{$partnerUser->first_name.' '.$partnerUser->surname}}
                                        </a>
                                    </li>
                                    <li>
                                        <p>
                                            <br>
                                            {{$contact->commission ? $contact->commission . '% - ' : ''}} {{$contact->approved ? 'Approved' : ($contact->agreement_sign == 'sent' ? 'Sent to Sign' : 'Not Approved')}}
                                            {{$contact->review ? ' - '.'Sent to Review' : ''}}
                                            {{$contactPDetails['city'] != '' ? ' - '.$contactPDetails['city'] : ''}}
                                            {{$contactPDetails['skills'] != '' ? ' - '.$contactPDetails['skills'] : ''}}
                                            <br>
                                            {{$contact->code}}
                                        </p>
                                    </li>
                                </ul>
                            </div>

                            <div class="m_btm_right_icons">
                                <ul>
                                    @if($isAgentUser)
                                    <li>
                                        <div title="Edit" data-id="{{$contact->id}}" class="m_btn_right_icon_each m_btm_edit active">
                                            <i class="fa fa-pencil"></i>
                                        </div>
                                    </li>
                                    @endif
                                    <li>
                                        <a title="Chat" class="m_btn_right_icon_each m_btn_chat active" data-id="{{$contact->id}}">
                                            <i class="fas fa-comment-dots"></i>
                                        </a>
                                    </li>
                                    @if($contact->approved)
                                    <li>
                                        <a title="Calendar" class="m_btn_right_icon_each m_btn_calendar active" data-id="{{$contact->id}}">
                                            <i class="fa fa-calendar"></i>
                                        </a>
                                    </li>
                                    @else
                                    <li>
                                        <a title="Calendar" class="m_btn_right_icon_each" onclick="openModal()">
                                            <i class="fa fa-calendar"></i>
                                        </a>
                                    </li>
                                    @endif
                                    <li>
                                        <a data-open="blank" title="View Submission" data-id="{{route('agent.contact.details',['code' => $contact->code])}}" class="m_btn_right_icon_each m_btm_view active">
                                            <i class="fa fa-file-text-o"></i>
                                        </a>
                                    </li>
                                    @if($contact->approved)
                                    <li>
                                        <a title="Agreements" class="m_btn_right_icon_each m_btn_files active" data-id="{{$contact->id}}">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                    </li>
                                    @else
                                    <li>
                                        <a title="Agreements" class="m_btn_right_icon_each" onclick="openModal()">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                    </li>
                                    @endif

                                    <li>
                                        @if($partnerUser->username)
                                        <a target="_blank" title="Website" href="{{route('user.home',['params' => $partnerUser->username])}}" class="m_btn_right_icon_each m_btm_website active">
                                            <i class="fa fa-home"></i>
                                        </a>
                                        @else
                                        <a title="Website" href="javascript:void(0)" onclick="openModal()" class="m_btn_right_icon_each m_btm_website">
                                            <i class="fa fa-home"></i>
                                        </a>
                                        @endif
                                    </li>

                                    @if($isAgentUser)
                                    <li>
                                        <a title="Delete" class="m_btn_right_icon_each m_btm_del active" data-del-id="{{$contact->id}}">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </div>

                        <div class="edit_elem_bottom">
                            @if($isAgentUser)
                            <div class="each_dash_section instant_hide" data-id="contact_edit_{{$contact->id}}">
                                @include('parts.agent-contact-edit-template', ['contact' => $contact, 'partnerUser' => $partnerUser])
                            </div>
                            @endif
                            @if($contact->approved)
                            <div class="each_dash_section instant_hide" data-id="contact_calendar_{{$contact->id}}">

                            </div>
                            <div class="each_dash_section instant_hide" data-id="contact_agreement_{{$contact->id}}">
                                @include('parts.agent-contact-agreement', ['contact' => $contact, 'contracts' => $contracts, 'hasActiveSub' => $hasActiveSub])
                            </div>
                            @endif
                            <div class="each_dash_section instant_hide" data-id="contact_chat_{{$contact->id}}">
                                @include('parts.agent-contact-chat')
                            </div>
                        </div>
                    </div>
                @endforeach
            @elseif(isset($userGroups) && count($userGroups))
            @php $counter = 0 @endphp
                @foreach($userGroups as $key => $group)
                    @php $counter++ @endphp
                    @php
                        $contactUser = $group->contact;
                        $agentUser = $group->agent;
                        $partnerUser = Auth::user()->id == $contactUser->id ? $agentUser : $contactUser;
                        $contactPDetails = $commonMethods->getUserRealDetails($partnerUser->id)
                    @endphp
                    <div data-partner="{{$partnerUser->id}}" class="clearfix agent_supporter_listing music_btm_list no_sorting">
                        <div class="edit_elem_top">
                            <div class="m_btm_list_left">
                                @if($counter < 10)
                                <div class="music_btm_thumb">
                                    <img src="{{$contactPDetails['image']}}" alt="">
                                </div>
                                @else
                                <div data-image="{{$contactPDetails['image']}}" class="music_btm_thumb">
                                    <div class="music_bottom_load_thumb">Load Image</div>
                                </div>
                                @endif
                                <ul class="music_btm_img_det">
                                    <li>
                                        <a class="filter_search_target" href="">
                                            {{$partnerUser->first_name.' '.$partnerUser->surname}}
                                        </a>
                                    </li>
                                    <li><p>{{$partnerUser->email}}</p></li>
                                    <li>
                                        <p>
                                            <br>
                                            {{$contactPDetails['city'] != '' ? $contactPDetails['city'] : ''}}
                                            {{$contactPDetails['country'] != '' ? ' - '.$contactPDetails['country'] : ''}}
                                        </p>
                                    </li>
                                </ul>
                            </div>

                            <div class="m_btm_right_icons">
                                <ul>
                                    <li>
                                        <a title="Chat" class="m_btn_right_icon_each m_btn_chat active" data-id="{{$group->id}}">
                                            <i class="fas fa-comment-dots"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a title="Calendar" class="m_btn_right_icon_each m_btn_calendar active" data-id="{{$group->id}}">
                                            <i class="fa fa-calendar"></i>
                                        </a>
                                    </li>
                                    @if(Auth::user()->id == $agentUser->id)
                                    <li>
                                        <a title="Delete" class="m_btn_right_icon_each m_btm_del active" data-del-id="{{$group->id}}">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </div>

                        <div class="edit_elem_bottom">
                            <div class="each_dash_section instant_hide" data-id="supporter_chat_{{$group->id}}">
                                @include('parts.agent-contact-chat', ['hideButtons' => $user->is_buyer_only ? true : false])
                            </div>
                            <div class="each_dash_section instant_hide" data-id="supporter_calendar_{{$group->id}}">

                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="no_results">No records yet</div>
            @endif
            </div>
            <div class="chat_filter_tab chat_filter_personal instant_hide">
            @if(!isset($userGroups) && count($user->personalChatPartners()) > 0)
                @foreach($user->personalChatPartners() as $partnerId)
                    @php $partner = \App\Models\User::find($partnerId) @endphp
                    @if(!$partner)
                        @php continue @endphp
                    @endif
                    @php $partnerPDetails = $commonMethods->getUserRealDetails($partnerId) @endphp
                    <div data-form="my-contact-form_{{ $partner->id }}" class="clearfix agent_partner_listing music_btm_list no_sorting">
                        <div class="edit_elem_top">
                            <div class="m_btm_list_left">
                                <div data-image="{{$partnerPDetails['image']}}" class="music_btm_thumb">
                                    <div class="music_bottom_load_thumb">Load Image</div>
                                </div>
                                <ul class="music_btm_img_det">
                                    <li>
                                        <a class="filter_search_target" href="">
                                            {{$partner->name}}
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="m_btm_right_icons">
                                <ul>
                                    <li>
                                        <a title="Chat" class="m_btn_right_icon_each m_btn_chat active" data-id="{{$partner->id}}">
                                            <i class="fas fa-comment-dots"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="edit_elem_bottom">
                            <div class="each_dash_section instant_hide" data-id="partner_chat_{{$partner->id}}">
                                @include('parts.agent-contact-chat', ['isPersonal' => true])
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="no_results">No records yet</div>
            @endif
            </div>
        </div>
    </div>
    <!-- Modals -->
    <div id="myModal">
        <div class="modal-container">
            <div class="modal-content">
                <div class="modal-header">
                    <button onclick="closeModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <p class="mb-4 text-lg">To use this feature, your contact must be approved.</p>
                </div>
                <div class="modal-footer">
                    <button onclick="closeModal()">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>

    function getContactCalendar(well, contact, type){

        $.ajax({

            url: "/informationFinder",
            dataType: "json",
            type: 'post',
            data: {'find_type': 'my-calendar', 'find': type, 'identity_type': 'subscriber', 'identity': contact},
            success: function(response) {

                if(response.success == 1){
                    well.html(response.data.data);
                    well.removeClass('instant_hide');
                }else{

                }
            }
        });
    }

    $('document').ready(function(){

        $('body').delegate( "input.pro_contact_commission", "keyup", function(e){

            let data = '';

            var value = $(this).val();
            if (value != '' && value != 0 && $(this).attr('data-has-terms') == '0') {
                data = 'Hi\n\nI hope this message finds you well! I am excited to invite you to connect with me on the 1Platform App, a fantastic tool that makes networking a breeze.\n\nIf there is ever a project or collaboration opportunity where I think we could team up, you will receive notifications. I will receive the agreed commission for any work created through our network chat. Do not worry; using the platform directly for your personal needs will not be affected—this commission is specific to collaborations in our chat area. To learn more about the commission feature, check out this explanatory video: [Insert URL].\n\nWe can also chat conveniently right there in the app.\n\nOnce you are on board, feel free to set up your account and explore all the features tailored for your career.\n\nLooking forward to connecting!';
                $(this).closest('.pro_stream_input_outer').find('textarea').html(data);
            } else if ((value == '' || value != 0) && $(this).attr('data-has-terms') == '0') {
                data = 'Hi\n\nHope you are doing well! I wanted to invite you to connect with me on the 1Platform App. It is a cool tool that makes networking easy.\n\nIf there is ever a project or anything where I think we could team up, you will get a heads-up through notifications. We can also chat right there in the app.\n\nOnce you are in, you can set up your account and use all the features for your career stuff.\n\nExcited to have you on board!';
                $(this).closest('.pro_stream_input_outer').find('textarea').html(data);
            }
        });

        $('.music_bottom_load_thumb').click(function(){
            $(this).addClass('instant_hide');
            var src = $(this).closest('.music_btm_thumb').attr('data-image');
            var img = '<img src="'+src+'">';
            $(this).closest('.music_btm_thumb').append(img);
        });

        setTimeout(function(){
            $('.m_btn_chat:first').trigger('click');
        }, 100);

        $('.m_btm_filter_dropdown').change(function(){

            var val = $(this).val();
            var target = $(this).attr('data-target');
            if(val == 'all' || val == ''){

                $('.'+target).removeClass('instant_hide');
            }else if(val == 'All Approved'){

                $('.'+target).each(function(e){
                    if($(this).attr('data-approved') == '1'){
                        $(this).removeClass('instant_hide');
                    }else{
                        $(this).addClass('instant_hide');
                    }
                });
            }else if(val == 'All Unapproved'){

                $('.'+target).each(function(e){
                    if($(this).attr('data-approved') == ''){
                        $(this).removeClass('instant_hide');
                    }else{
                        $(this).addClass('instant_hide');
                    }
                });
            }
        });

        $('.switch_personal_chat .smart_switch input').change(function(){

            $('.chat_filter_tab, .m_btm_filter_search, .m_btm_filter_drop, .chat_label').addClass('instant_hide');
            if($(this).prop("checked") == true){

                $('.chat_filter_tab.chat_filter_personal, .chat_label_personal').removeClass('instant_hide');
            }else{

                $('.chat_filter_tab.chat_filter_contacts, .chat_label_contacts, .chat_filter_tab.chat_filter_agent, .m_btm_filter_search, .m_btm_filter_drop').removeClass('instant_hide');
            }
        });

        $('.contact-edit-nav').click(function(){
            $(this).closest('.contact-edit-section').find('.contact-edit-section-right,.contact-edit-section-comp').slideToggle();
            $(this).find('i').toggleClass('fa-chevron-down fa-chevron-up');
        });

        $('.m_btm_edit, .m_btn_files, .m_btn_calendar, .m_btn_chat').click(function(e) {

            e.preventDefault();
            var id = $(this).attr('data-id');
            var mainP = $(this).closest('.music_btm_list');
            if ($(this).hasClass('m_btm_edit')) {
                mainP.find('.each_dash_section:not(.each_dash_section[data-id="contact_edit_' + id + '"])').addClass('instant_hide');
                mainP.find('.each_dash_section[data-id="contact_edit_' + id + '"]').toggleClass('instant_hide');
            } else if ($(this).hasClass('m_btn_files')) {
                mainP.find('.each_dash_section:not(.each_dash_section[data-id="contact_agreement_' + id + '"])').addClass('instant_hide');
                mainP.find('.each_dash_section[data-id="contact_agreement_' + id + '"]').toggleClass('instant_hide');
            } else if ($(this).hasClass('m_btn_calendar')) {


                if (mainP.hasClass('agent_contact_listing')) {
                    mainP.find('.each_dash_section:not(.each_dash_section[data-id="contact_calendar_' + id + '"])').addClass('instant_hide');
                    var elem = mainP.find('.each_dash_section[data-id="contact_calendar_' + id + '"]');
                    var type = 'contact';
                } else if (mainP.hasClass('agent_supporter_listing')) {
                    mainP.find('.each_dash_section:not(.each_dash_section[data-id="supporter_calendar_' + id + '"])').addClass('instant_hide');
                    var elem = mainP.find('.each_dash_section[data-id="supporter_calendar_' + id + '"]');
                    var type = 'supporter';
                }

                if (elem.hasClass('instant_hide')) {
                    getContactCalendar(elem, id, type);
                } else {
                    elem.addClass('instant_hide');
                }
            } else if ($(this).hasClass('m_btn_chat')) {
                if (mainP.hasClass('agent_contact_listing')) {

                    var elem = $('.each_dash_section[data-id="contact_chat_' + id + '"]');
                    var parent = $(this).closest('.agent_contact_listing');
                    var type = 'contact-chat';
                } else if (mainP.hasClass('agent_partner_listing')) {

                    var elem = $('.each_dash_section[data-id="partner_chat_' + id + '"]');
                    var parent = $(this).closest('.agent_partner_listing');
                    var type = 'partner-chat';
                } else if (mainP.hasClass('agent_supporter_listing')) {

                    var elem = $('.each_dash_section[data-id="supporter_chat_' + id + '"]');
                    var parent = $(this).closest('.agent_supporter_listing');
                    var type = 'supporter-chat';
                }

                parent.find('.each_dash_section').not(elem).addClass('instant_hide');
                elem.toggleClass('instant_hide');

                if (!elem.hasClass('instant_hide')) {

                    var formData = new FormData();
                    formData.append('type', type);
                    formData.append('data', id);

                    getChatMessages(elem.find('.chat_outer'), formData);
                }
            }
        });

        $('body').delegate('.m_btm_del', 'click', function(e){

            e.preventDefault();
            var id = $(this).attr('data-del-id');
            $('.pro_confirm_delete_outer #pro_delete_submit_yes').attr('data-delete-id', id);

            if ($(this).closest('.music_btm_list').hasClass('agent_contact_listing')) {

                $('.pro_confirm_delete_outer #pro_delete_submit_yes').attr('data-delete-item-type', 'agent-contact');
            } else if ($(this).closest('.music_btm_list').hasClass('agent_contact_request_listing')) {

                $('.pro_confirm_delete_outer #pro_delete_submit_yes').attr('data-delete-item-type', 'agent-contact-request');
            } else if ($(this).closest('.music_btm_list').hasClass('agent_supporter_request_listing')) {

                $('.pro_confirm_delete_outer #pro_delete_submit_yes').attr('data-delete-item-type', 'agent-supporter-request');
            }  else if ($(this).closest('.music_btm_list').hasClass('agent_supporter_listing')) {

                $('.pro_confirm_delete_outer #pro_delete_submit_yes').attr('data-delete-item-type', 'agent-supporter');
            }

            if (id) {

                $('.pro_confirm_delete_outer').show();
                $('#body-overlay').show();
            }
        });

            $('.m_btm_switch_account').click(function(e) {

                var id = $(this).attr('data-id');
                $('#switch_account_popup').attr('data-id', id);
                $('#switch_account_popup,#body-overlay').show();
            });

            $('.m_btm_navigate_contact_home').click(function(e) {

                var id = $(this).attr('data-id');
                window.location.href = id
            });

            $('#proceed_switch_account').click(function(e) {

                var id = $('#switch_account_popup').attr('data-id');
                window.location = id;
            });

            $('.m_btm_right_icons .m_btm_view').click(function(e) {

            var id = $(this).attr('data-id');
            if (id != '') {

                if ($(this).attr('data-open') == 'blank') {

                    window.open(id, '_blank');
                } else {
                    window.location = id;
                }
            }
        });

        $('.edit_now, .edit_and_send_agree, .edit_and_send_question').click(function(e) {

            e.preventDefault();
            var thiss = $(this);
            var error = 0;
            var form = thiss.closest('form');
            var email = form.closest('.contact-edit-container').find('input[name="pro_contact_email"]');
            var questionId = form.find('select[name="pro_contact_questionnaireId"]');
            form.find('.has-danger').removeClass('has-danger');
            if ($(this).hasClass('edit_and_send_agree')) {
                form.find('input[name="send_email"]').val('1');
            } else if ($(this).hasClass('edit_and_send_question')) {

                if (questionId.val() == '') {
                    error = 1;
                    questionId.closest('.pro_stream_input_each').addClass('has-danger');
                } else {
                    form.find('input[name="send_email"]').val('2');
                }
            } else {
                form.find('input[name="send_email"]').val('0');
            }

            if (!error) {
                form.submit();
            }
        });

        $('.switch_contracts_view').click(function(e) {

            var form = $(this).closest('form');
            form.find('.contracts_list').toggleClass('instant_hide');
            form.find('.new_contracts').toggleClass('instant_hide');
            if ($(this).attr('data-list') == 'add') {
                $(this).attr('data-list', 'list');
                $(this).text('Add contract');
            } else if ($(this).attr('data-list') == 'list') {
                $(this).attr('data-list', 'add');
                $(this).text('My contracts');
            }
        });

        $('.chat_main_body .chat_main_body_messages').on('scroll', function() {

            var thiss = $(this);
            var parent = thiss.closest('.chat_outer');
            if(thiss.get(0).scrollTop == 0 && thiss.find('.chat_each_message').length > 0){
                parent.find('.loading_messages').removeClass('instant_hide');
                var firstMessage = thiss.find('.chat_each_message').first();

                if(parent.closest('.music_btm_list').hasClass('agent_contact_listing')){
                    var id = parent.closest('.agent_contact_listing').find('.m_btn_right_icon_each.m_btn_chat').attr('data-id');
                    $type = 'contact-chat';
                }else if(parent.closest('.music_btm_list').hasClass('agent_partner_listing')){
                    var id = parent.closest('.agent_partner_listing').find('.m_btn_right_icon_each.m_btn_chat').attr('data-id');
                    $type = 'partner-chat';
                }else if(parent.closest('.music_btm_list').hasClass('agent_supporter_listing')){
                    var id = parent.closest('.agent_supporter_listing').find('.m_btn_right_icon_each.m_btn_chat').attr('data-id');
                    $type = 'supporter-chat';
                }

                var formData = new FormData();
                formData.append('type', $type);
                formData.append('data', id);
                formData.append('cursor', firstMessage.attr('data-cursor'));
                getChatMessages(parent, formData);
            }
        });

        $('.chat_outer .submit_btn').click(function(){

            var thiss = $(this);
            var parent = thiss.closest('.chat_outer');
            var attachments = parent.find('.chat_attachments');
            var message = parent.find('.new_message');

            if($(this).closest('.music_btm_list').hasClass('agent_contact_listing')){

                var id = parent.closest('.agent_contact_listing').find('.m_btn_right_icon_each.m_btn_chat').attr('data-id');
                $type = 'contact';
            }else if($(this).closest('.music_btm_list').hasClass('agent_partner_listing')){

                var id = parent.closest('.agent_partner_listing').find('.m_btn_right_icon_each.m_btn_chat').attr('data-id');
                $type = 'partner';
            }else if($(this).closest('.music_btm_list').hasClass('agent_supporter_listing')){

                var id = parent.closest('.agent_supporter_listing').find('.m_btn_right_icon_each.m_btn_chat').attr('data-id');
                $type = 'supporter';
            }

            if(message.val() != '' || attachments.val() != ''){

                thiss.addClass('disabled');

                if(attachments.val() != ''){

                    prepareChatUploader(parent);
                    $('.pro_pop_chat_upload,#body-overlay').show();
                    startChatUploader(parent);
                }else{

                    var formData = new FormData();
                    formData.append($type, id);
                    formData.append('message', message.val());
                    formData.append('action', 'send-message');

                    $.ajax({

                        url: '/dashboard/create-message',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: "json",
                        success: function (response) {

                            thiss.removeClass('disabled');

                            if(response.success){
                                parent.find('.attachment_area .close').trigger('click');
                                refreshChat(parent);
                            }else{
                                console.log(response.error);
                            }

                            parent.find('.new_message').val('').change();
                        }
                    });
                }
            }
        });

        $('.chat_main_body_attach').click(function(e){

            e.preventDefault();
            var parent = $(this).closest('.chat_outer');
            parent.find('.chat_attachments').trigger('click');
        });

        $('.attachment_area .close').click(function(){

            var parent = $(this).closest('.chat_outer');
            parent.find('.chat_attachments').val('');
            parent.find('.attachment_area .each_attach').remove();
            parent.find('.chat_main_body_foot').removeClass('attachment');
        });

        $('body').delegate('.m_btm_filter_search_field div', 'input', function(e){
            var searchStr = $(this).text().toLowerCase();
            var target = $(this).attr('data-target');
            if(searchStr.length > 0){
                $('.'+target).each(function(e){
                    if($(this).find('.filter_search_target').text().toLowerCase().indexOf(searchStr) == -1){
                        $(this).addClass('instant_hide');
                    }else{
                        $(this).removeClass('instant_hide');
                    }
                });
            }else if(searchStr.length == 0){
                $('.'+target).removeClass('instant_hide');
            }
        });

        $('.chat_attachments').change(function(){

            var parent = $(this).closest('.chat_outer');
            if(this.files && this.files[0]){
                for(var i = 0; i < this.files.length; i++){
                    if(this.files[i].type == 'image/jpeg' || this.files[i].type == 'image/png'){
                        if(this.files[i].size > 5*1024*1024){
                            alert('Your file '+this.files[i].name+' is more than 5MB');
                        }else{
                            var reader = new FileReader();
                            reader.onload = imageIsLoaded;
                            reader.readAsDataURL(this.files[i]);
                        }
                    }else{
                        if(this.files[i].size > 100*1024*1024){
                            alert('Your file '+this.files[i].name+' is more than 5MB');
                        }else{
                            var extension = this.files[i].name.split('.').pop();
                            parent.find('.attachment_area').append('<div class="each_attach file"><div class="up">File</div><div class="down"><i class="fa fa-file-o"></i></div></div>');
                            parent.find('.chat_main_body_foot').addClass('attachment');
                            parent.find('.new_message').focus();
                        }
                    }
                }
            }
        });

        $('body').delegate('.chat_outer:not(.disabled) .refresh_messages', 'click', function(e){

            var element = $(this).closest('.chat_outer');
            element.find('.loading_messages').removeClass('instant_hide');
            element.find('.chat_main_body_messages').html('');

            refreshChat(element);
        });

        $('body').delegate('.chat_outer:not(.disabled) .proffer_project_btn:not(.disabled),.chat_outer:not(.disabled) .add_product_btn:not(.disabled),.chat_outer:not(.disabled) .add_agreement_btn:not(.disabled)', 'click', function(e){

            var element = $(this).closest('.chat_outer');
            var type = null;
            var members = [];
            var purchaseItem = null;
            var purchaseType = null;
            var customers = [];

            if($(this).hasClass('proffer_project_btn')){
                purchaseItem = 'project';
            }else if($(this).hasClass('add_product_btn')){
                purchaseItem = 'product';
            }else if($(this).hasClass('add_agreement_btn')){
                purchaseItem = 'license';
            }

            if(purchaseItem){

                $('#chat_purchase_popup .stage, #chat_purchase_popup .stage_two').addClass('instant_hide');
                if(purchaseItem == 'project'){

                    var stage = $('#chat_purchase_popup .stage_one .project');
                }else if(purchaseItem == 'product'){

                    var stage = $('#chat_purchase_popup .stage_one .product');
                }else if(purchaseItem == 'license'){

                    var stage = $('#chat_purchase_popup .stage_one .license');
                }

                stage.find('.choose_customer_dropdown').find('option').each(function() {
                    if($(this).val() != ''){
                        $(this).remove();
                    }
                });
                stage.find('input,textarea').val('');

                if(element.closest('.music_btm_list').hasClass('agent_contact_listing') && element.closest('.music_btm_list.agent_contact_listing').attr('data-approved') == '1'){

                    var id = element.closest('.agent_contact_listing').find('.m_btn_right_icon_each.m_btn_chat').attr('data-id');
                    purchaseType = 'group-purchase';
                    customers.push({ key: 'partner', value: element.closest('.music_btm_list.agent_contact_listing').find('.filter_search_target').text() });
                    element.find('.chat_member_each_outer:not(.chat_member_add)').each(function(){
                        customers.push({ key: $(this).attr('data-member'), value: $(this).attr('data-name') });
                    });
                }else if(element.closest('.music_btm_list').hasClass('chat_group_listing')){

                    var id = element.closest('.agent_contact_listing').find('.m_btn_right_icon_each.m_btn_chat').attr('data-id');
                    purchaseType = 'group-purchase';
                    element.find('.chat_member_each_outer:not(.chat_member_add)').each(function(){
                        customers.push({ key: $(this).attr('data-member'), value: $(this).attr('data-name') });
                    });
                }else if(element.closest('.music_btm_list').hasClass('agent_partner_listing')){

                    var id = element.closest('.agent_partner_listing').find('.m_btn_right_icon_each.m_btn_chat').attr('data-id');
                    purchaseType = 'partner-purchase';
                    customers.push({ key: id, value: element.closest('.music_btm_list.agent_partner_listing').find('.filter_search_target').text() });
                }else if(element.closest('.music_btm_list').hasClass('agent_contact_listing') && element.closest('.music_btm_list.agent_contact_listing').attr('data-approved') == ''){

                    var id = element.closest('.agent_contact_listing').attr('data-partner');
                    purchaseType = 'partner-purchase';
                    customers.push({ key: id, value: element.closest('.music_btm_list.agent_contact_listing').find('.filter_search_target').text() });
                }else if(element.closest('.music_btm_list').hasClass('agent_supporter_listing')){

                    var id = element.closest('.agent_supporter_listing').find('.m_btn_right_icon_each.m_btm_del').attr('data-del-id');
                    purchaseType = 'supporter-purchase';
                    customers.push({ key: 'partner', value: element.closest('.music_btm_list.agent_supporter_listing').find('.filter_search_target').text() });
                }

                for (var i = 0; i < customers.length; i++) {
                    stage.find('.choose_customer_dropdown').append($('<option>',{
                        value: customers[i].key,
                        text : customers[i].value
                    }));
                }
                $('#chat_purchase_popup').attr({
                    'data-type': purchaseType,
                    'data-item': purchaseItem,
                    'data-id': id
                });
                stage.removeClass('instant_hide');
                $('#chat_purchase_popup .stage_one').removeClass('instant_hide');
                $('#chat_purchase_popup,#body-overlay').show();
            }else{

                alert('Invalid option');
            }
        });

        $('body').delegate('.choose_end_term_dropdown', 'change', function(e){
            if($(this).val() == 'custom'){
                $(this).closest('.pro_pop_multi_row').find('.end_term').prop('disabled', false).focus();
                $('#term-date').prop('disabled', false).focus();
                $('#project-date').prop('disabled', false).focus();
            }else{
                $(this).closest('.pro_pop_multi_row').find('.end_term').val('').prop('disabled', true);
                $('#term-date').prop('disabled', true);
                $('#project-date').prop('disabled', true);
            }
        });

        $('body').delegate('.chat_purchase_send_btn', 'click', function(e){

            var thiss = $(this);
            $('.stage .error').addClass('instant_hide');
            var error = 0;
            var purchaseType = $('#chat_purchase_popup').attr('data-type');
            var purchaseItem = $('#chat_purchase_popup').attr('data-item');
            var purchaseId = $('#chat_purchase_popup').attr('data-id');
            var formData = new FormData();

            if(purchaseItem == 'project'){

                var stage = $('#chat_purchase_popup .stage_one .project');
                var url = '/proffer-project/add';
            }else if(purchaseItem == 'product'){

                var stage = $('#chat_purchase_popup .stage_one .product');
                var url = '/proffer-product/add';
            }else if(purchaseItem == 'license'){

                var stage = $('#chat_purchase_popup .stage_one .license');
                var url = '/bispoke-license/agreement/add';
            }else{

                error = 1;
                alert('Incorrect purchase');
            }
            if(error == 0){

                var customer = stage.find('.choose_customer_dropdown');
                var title = stage.find('.title');
                var endTermSelect = stage.find('.choose_end_term_dropdown');
                var endTerm = stage.find('.end_term');
                var price = stage.find('.price');
                var description = stage.find('.description');
                var product = stage.find('.choose_product');
                var music = stage.find('.choose_agreement_music');
                var license = stage.find('.choose_agreement_license');
                var licenseTerms = stage.find('.license_terms');
                if(stage.find('.choose_customer_dropdown').length > 0){
                    if(customer.val() == ''){
                        stage.find('.choose_customer_error').removeClass('instant_hide');
                        error = 1;
                    }else{
                        formData.append('customer', customer.val());
                    }
                }
                if(purchaseItem == 'project' && $('#project-title').length > 0) {
                    formData.append('title', $('#project-title').val());
                } else if(stage.find('.title').length > 0){

                    if(title.val() == ''){
                        stage.find('.title_error').removeClass('instant_hide');
                        error = 1;
                    }else{
                        formData.append('title', title.val());
                    }
                }

                if(purchaseItem == 'license') {
                    formData.append('price', $('#term-price').val());
                } else if (purchaseItem == 'product') {
                    formData.append('price', $('#product-price').val());
                } else if (purchaseItem == 'project') {
                    formData.append('price', $('#project-price').val());
                } else if(stage.find('.price').length > 0){
                    if(price.val() == ''){
                        stage.find('.price_error').removeClass('instant_hide');
                        error = 1;
                    }else{
                        formData.append('price', price.val());
                    }
                }

                if(stage.find('.description').length > 0){

                    if(description.val() == ''){
                        stage.find('.description_error').removeClass('instant_hide');
                        error = 1;
                    }else{
                        formData.append('description', description.val());
                    }
                }
                if(stage.find('.choose_end_term_dropdown').length > 0){

                    if(endTermSelect.val() == ''){
                        stage.find('.choose_end_term_error').removeClass('instant_hide');
                        error = 1;
                    }else if(endTermSelect.val() == 'custom' && (endTerm.val() == '' && $('#term-date').val() == '' && $('#project-date').val() == '')){
                        stage.find('.end_term_error').removeClass('instant_hide');
                        error = 1;
                    }else{
                        formData.append('endTermSelect', endTermSelect.val());
                        if($('#term-date').length) {
                            formData.append('endTerm', $('#term-date').val());
                        } else if($('#project-date').length) {
                            formData.append('endTerm', $('#project-date').val());
                        } else {
                            formData.append('endTerm', endTerm.val());
                        }
                    }
                }
                if(stage.find('.choose_product').length > 0){
                    if(product.val() == ''){
                        stage.find('.choose_product_error').removeClass('instant_hide');
                        error = 1;
                    }else{
                        formData.append('product', product.val());
                    }
                }
                if(stage.find('.choose_agreement_music').length > 0){
                    if(music.val() == ''){
                        stage.find('.choose_agreement_music_error').removeClass('instant_hide');
                        error = 1;
                    }else{
                        formData.append('music', music.val());
                    }
                }
                if(stage.find('.choose_agreement_license').length > 0){
                    if(license.val() == ''){
                        stage.find('.choose_agreement_license_error').removeClass('instant_hide');
                        error = 1;
                    }else{
                        formData.append('license', license.val());
                    }
                }
                if(stage.find('.license_terms').length > 0){
                    formData.append('terms', licenseTerms.val());
                }
                if(error){
                    console.log('error');
                }else{
                    formData.append('type', purchaseType);
                    formData.append('id', purchaseId);

                    $.ajax({

                        url: url,
                        dataType: "json",
                        type: 'post',
                        cache: false,
                        contentType: false,
                        enctype: 'multipart/form-data',
                        processData: false,
                        data: formData,
                        success: function(response) {
                            if(response.success == 1){
                                var name = customer.find('option:selected').text();
                                $('#chat_purchase_popup .stage_two #sender_name').text(name);
                                $('#chat_purchase_popup .stage_one').addClass('instant_hide');
                                $('#chat_purchase_popup .stage_two').removeClass('instant_hide');
                                $('#chat_purchase_popup input, #chat_purchase_popup textarea').val('');

                                var element = $('div[data-form="my-contact-form_'+purchaseId+'"]');
                                if(element.length == 0){
                                    element = $('div[data-partner="'+purchaseId+'"]');
                                }
                                if(element.length){
                                    refreshChat(element.find('.chat_outer'));
                                }
                            }else{
                                alert(response.error);
                            }
                        }
                    });
                }
            }
        });

        $('body').delegate('.chat_member_add', 'click', function(e){
            var id = $(this).attr('data-id');
            var contact = $(this).closest('.agent_contact_listing').find('.m_btn_right_icon_each.m_btn_chat').attr('data-id');
            $('#add_chat_group_member_popup #add_chat_group_member').val('');
            $('#add_chat_group_member_popup').attr('data-group', id);
            $('#add_chat_group_member_popup').attr('data-contact', contact);
            $('#add_chat_group_member_popup,#body-overlay').show();
            e.stopPropagation();
        });

        $('body').delegate('.chat_member_remove', 'click', function(e){
            e.stopPropagation();
            var group = $(this).closest('.chat_group_members').find('.chat_member_add').attr('data-id');
            var member = $(this).closest('.chat_member_each_outer');
            if(confirm('Are you sure?')){

                var formData = new FormData();
                formData.append('group', group);
                formData.append('contact', member.attr('data-member'));
                formData.append('action', 'remove');

                $.ajax({

                    url: '/agent-contact/add-remove-to-group',
                    type: 'POST',
                    data: formData,
                    contentType:false,
                    cache: false,
                    processData: false,
                    dataType: 'json',
                    success: function (response) {

                        if(response.success){

                            $('.pro_page_pop_up, #body-overlay').hide();
                            member.remove();
                        }else{
                            alert(response.error);
                        }
                    }
                });
            }
        });
    });

    function getChatMessages(element, formData) {

        $.ajax({

            url: '/dashboard/chat',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(response) {

                element.find('.loading_messages').addClass('instant_hide');
                if (response.success) {
                    if (response.data.group.messages.length) {

                        renderChatMessages(element, formData.get('cursor'), response.data.group);
                    } else if (response.data.private.messages.length) {

                        renderChatMessages(element, formData.get('cursor'), response.data.private);
                    } else {

                        if (!formData.get('cursor')) {
                            element.find('.chat_main_body_messages').html('<div class="m-auto text-sm font-semibold text-center text-gray-500">No messages found</div>');
                        }
                    }
                } else {

                    console.log(response.error);
                }
            }
        });
    }

    function renderChatMessages(element, cursor, data) {

        if (cursor) {

            element.find('.chat_main_body_messages').prepend(data.messages);
            renderScrollHeight(element, cursor);
        } else {

            element.find('.chat_main_body_messages').html(data.messages);
            renderScrollHeight(element);
        }

        if (data.members) {

            element.find('.chat_group_members').html(data.members);
        }
    }

    function renderScrollHeight(element, cursor = null) {

        if (cursor) {

            var height = 0;
            element.find('.chat_main_body_messages .chat_each_message').filter(function() {
                if ($(this).attr('data-cursor') < parseInt(cursor)) {

                    height += $(this).outerHeight(true);
                }
            });
            element.find('.chat_main_body_messages').animate({
                scrollTop: height
            }, 0);
        } else {

            setTimeout(function() {
                element.find('.chat_main_body_messages').animate({
                    scrollTop: element.find('.chat_main_body_messages')[0].scrollHeight + 5000
                }, 0);
            }, 100);
        }
    }

    function refreshChat(element) {

        if (element.closest('.music_btm_list').hasClass('agent_contact_listing')) {

            var id = element.closest('.agent_contact_listing').find('.m_btn_right_icon_each.m_btn_chat').attr('data-id');
            $type = 'contact-chat';
        } else if (element.closest('.music_btm_list').hasClass('agent_partner_listing')) {
            var id = element.closest('.agent_partner_listing').find('.m_btn_right_icon_each.m_btn_chat').attr('data-id');
            $type = 'partner-chat';
        } else if (element.closest('.music_btm_list').hasClass('agent_supporter_listing')) {
            var id = element.closest('.agent_supporter_listing').find('.m_btn_right_icon_each.m_btn_chat').attr('data-id');
            $type = 'supporter-chat';
        }
        var formData = new FormData();
        formData.append('type', $type);
        formData.append('data', id);

        getChatMessages(element, formData);
    }

    function prepareChatUploader(element) {

        var attachments = element.find('.chat_attachments')[0].files;
        var html = $('#pro_pop_chat_upload_sample').html();

        $(html).appendTo($('.pro_pop_chat_upload .pro_body_in'));
        var item = $('.pro_pop_chat_upload .pro_body_in .pro_pop_chat_upload_each').last();
        item.attr('data-type', 'attachment-initialize');
        item.find('.item_name').text('Initializing');
        item.find('.item_info').removeClass('instant_hide');

        for (var index = 0; index < attachments.length; index++) {
            $(html).appendTo($('.pro_pop_chat_upload .pro_body_in'));
            var item = $('.pro_pop_chat_upload .pro_body_in .pro_pop_chat_upload_each').last();
            item.attr('data-type', 'attachment-upload');
            item.attr('data-id', index);
            var size = attachments[index].size;
            var name = attachments[index].name;
            var sizeem = Math.round(size / (1024 * 1024));
            var sizeek = Math.round(size / (1024));
            item.find('.item_size').text(sizeem > 0 ? sizeem + ' MB' : sizeek + ' KB');
            item.find('.item_name').text(name);
            item.find('.item_file').removeClass('instant_hide');
        }
        $(html).appendTo($('.pro_pop_chat_upload .pro_body_in'));
        var item = $('.pro_pop_chat_upload .pro_body_in .pro_pop_chat_upload_each').last();
        item.attr('data-type', 'attachment-finalize');
        item.find('.item_name').text('Finalizing');
        item.find('.item_info').removeClass('instant_hide');
    }

    function startChatUploader(element, id = null) {

        var popElem = $('.pro_pop_chat_upload .pro_pop_chat_upload_each.waiting').first();
        if (popElem.length) {

            popElem.addClass('pending').removeClass('failed');
            popElem.find('.item_status i').addClass('fa-spinner fa-spin').removeClass('fa-check-circle fa-pause');

            var formData = new FormData();
            var dataType = popElem.attr('data-type');

            if (dataType == 'attachment-finalize') {

                let pId = null;
                if(element.closest('.music_btm_list').hasClass('agent_contact_listing')){
                    $type = 'contact';
                    pId = element.closest('.agent_contact_listing').find('.m_btn_right_icon_each.m_btn_chat').attr('data-id');
                }else if(element.closest('.music_btm_list').hasClass('agent_partner_listing')){
                    $type = 'partner';
                    pId = element.closest('.agent_partner_listing').find('.m_btn_right_icon_each.m_btn_chat').attr('data-id');
                }else if(element.closest('.music_btm_list').hasClass('agent_supporter_listing')){
                    $type = 'supporter';
                    pId = element.closest('.agent_supporter_listing').find('.m_btn_right_icon_each.m_btn_chat').attr('data-id');
                }

                formData.append('chat', id);
                formData.append($type, pId);
                formData.append('message', element.find('.new_message').val());
            } else if (dataType == 'attachment-upload') {

                var attachments = element.find('.chat_attachments')[0].files;
                formData.append('attachment', attachments[popElem.attr('data-id')]);
                formData.append('chat', id);
            }

            formData.append('action', dataType);
            $.ajax({

                url: '/dashboard/create-message',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                dataType: "json",
                enctype: 'multipart/form-data',
                success: function(response) {

                    if (response.success == 1) {
                        popElem.removeClass('pending waiting').addClass('complete');
                        popElem.find('.item_status i').removeClass('fa-spinner fa-spin').addClass('fa-check-circle');
                    } else {
                        popElem.removeClass('pending waiting').addClass('failed');
                        popElem.find('.item_status i').removeClass('fa-spinner fa-spin').addClass('fa-exclamation-triangle');
                    }

                    startChatUploader(element, response.id);
                },
                error: function(response) {
                    popElem.removeClass('pending waiting').addClass('failed');
                    popElem.find('.item_status i').removeClass('fa-spinner fa-spin').addClass('fa-exclamation-triangle');
                }
            });
        } else {
            element.find('.attachment_area .close').trigger('click');
            refreshChat(element);

            element.find('.new_message').val('').change();
            element.find('.attachment_area .close').trigger('click');
            $('.pro_pop_chat_upload .pro_pop_chat_upload_each').remove();
            $('.pro_pop_chat_upload,#body-overlay').hide();
            element.find('.submit_btn').removeClass('disabled');
        }
    }

    function chatPurchaseAction(element, type, value, id, account, seller, price, itemId) {

        var price = atob(price);
        var curr = $('#pay_quick_popup').attr('data-currency');
        var parent = $(element).closest('.chat_outer');
        var customId;
        var customType;

        if (type == 'project') {
            url = '/proffer-project/response';
            customId = '';
            customType = 'project';
        } else if (type == 'proferred-product') {
            url = '/proffer-product/response';
            customId = '';
            customType = 'product';
        } else if (type == 'instant-license') {
            url = '/bispoke-license/agreement/response';
            customId = 'bespoke_' + id;
            customType = 'license';
        }

        if (value == 'Accepted' || value == 'Declined') {
            if (confirm('Be sure to read the project file before proceding. Are you sure to proceed?')) {
                var formData = new FormData();
                formData.append('response', value);
                formData.append(customType, id);
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {

                            if (value == 'Accepted') {
                                if ((parent.closest('.music_btm_list').hasClass('agent_contact_listing') && parent.closest('.music_btm_list.agent_contact_listing').attr('data-approved') == '1') || (parent.closest('.music_btm_list').hasClass('chat_group_listing'))) {
                                    var response = preparePayInstant(account, id, '#pay_quick_card_number', '#pay_quick_card_expiry', '#pay_quick_card_cvc', 'You are purchasing a ' + customType, 'Price: ' + curr + price);
                                    if (response == '') {
                                        $('#pay_quick_popup,#body-overlay').show();
                                    } else {
                                        alert(response);
                                    }
                                } else {
                                    addCartItem(customId, type, 0, 0, 0, price, seller, id);
                                }
                            } else {
                                refreshChat(parent);
                            }
                        } else {
                            console.log(response);
                        }
                    }
                });
            }
        } else if (value == 'addToCart') {

            if ((parent.closest('.music_btm_list').hasClass('agent_contact_listing') && parent.closest('.music_btm_list.agent_contact_listing').attr('data-approved') == '1') || (parent.closest('.music_btm_list').hasClass('chat_group_listing'))) {
                var response = preparePayInstant(account, id, '#pay_quick_card_number', '#pay_quick_card_expiry', '#pay_quick_card_cvc', 'You are purchasing a ' + customType, 'Price: ' + curr + price);
                if (response == '') {
                    $('#pay_quick_popup,#body-overlay').show();
                } else {
                    alert(response);
                }
            } else {
                addCartItem(customId, type, 0, 0, 0, price, seller, id);
            }
        }
    }

    function preparePayInstant(account, id, cardNumberF, cardExpiryF, cardCvcF, mainText, subText) {

        var error = '';

        if (account === '') {

            error = 'This seller has not connected the stripe account'
        } else if (account === null) {

            window.stripe = Stripe($('#stripe_publishable_key').val());
        } else {

            var account = atob(account);
            window.stripe = Stripe($('#stripe_publishable_key').val(), {
                stripeAccount: account
            });
        }

        if (error != '') {

            return error;
        } else {

            var elements = window.stripe.elements();

            var baseStyles = {
                'fontFamily': 'Open sans, sans-serif',
                'fontSize': '14px',
                'color': '#000',
                'lineHeight': '31px'
            };
            var invalidStyles = {
                'color': '#fc064c'
            };

            window.eCardNumber = elements.create('cardNumber', {
                'style': {
                    'base': baseStyles,
                    'invalid': invalidStyles
                }
            });
            window.eCardCvc = elements.create('cardCvc', {
                'style': {
                    'base': baseStyles,
                    'invalid': invalidStyles
                }
            });
            window.eCardExpiry = elements.create('cardExpiry', {
                'style': {
                    'base': baseStyles,
                    'invalid': invalidStyles
                }
            });

            window.eCardNumber.mount(cardNumberF);
            window.eCardCvc.mount(cardCvcF);
            window.eCardExpiry.mount(cardExpiryF);

            $('#pay_quick_popup #pay_quick_error').text('').removeClass('instant_hide');
            $('#pay_quick_popup').attr('data-id', id);

            if (id.includes('custom_product')) {

                var split = subText.split('_');
                $('#pay_quick_popup .pay_item_name').text(limitString(split[4], 40));
                $('#pay_quick_popup .pay_item_price').text(split[2]);
                $('#pay_quick_popup .pay_item_purchase_qua .pay_item_purchase_qua_num').text(split[3]);
                $('#pay_quick_popup .pay_item_purchase_det').text(split[0]);
                if (split[1] != '') {
                    $('#pay_quick_popup .pay_item_purchase_det').text($('#pay_quick_popup .pay_item_purchase_det').text() + ' - ' + split[1]);
                }
            } else {

                $('#pay_quick_popup .main_headline').text(mainText);
                $('#pay_quick_popup .second_headline').html(subText);
            }
        }

        return '';
    }

    function openModal() {
        document.getElementById('myModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('myModal').style.display = 'none';
    }

</script>
<style>
    #myModal {
        display: none;
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 50;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-container {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
    }

    .modal-content {
        max-width: 400px; /* Adjust the max-width as needed */
        width: 100%;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        overflow: hidden;
    }

    .modal-header {
        display: flex;
        justify-content: flex-end;
        padding: 1rem;
    }

    .modal-header button {
        color: #666;
        cursor: pointer;
        font-size: 1.5rem;
    }

    .modal-body {
        padding-right: 2rem;
        padding-left: 2rem;
        text-align: center;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        padding: 0rem 1rem 1rem 0rem;
    }

    .modal-footer button {
        background-color: #3490dc;
        color: #fff;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        cursor: pointer;
    }
</style>
