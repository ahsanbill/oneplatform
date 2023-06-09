<form action="{{route('agent.contact.update')}}" method="POST">
    {{ csrf_field() }}

    <input type="hidden" name="edit" value="{{$contact->id}}">
    <input type="hidden" name="send_email" value="0">

    <div class="pro_music_info">
        <div class="pro_form_title">Edit Contact</div>
        <div class="pro_stream_input_outer">

            <div class="pro_stream_input_each">
                <input class="dummy_field" type="text" name="email">
                <input {{$contact->approved ? 'readonly' : ''}} value="{{$contact->email}}" placeholder="Email" type="text" class="pro_stream_input" name="pro_contact_email" />
            </div>

            <div class="pro_stream_input_each stream_sec_opt_outer">
                <select name="pro_contact_questionnaireId">
                    <option value="">Choose questionnaire</option>
                    @if(count($user->questionnaires))
                        @foreach($user->questionnaires as $questionnaire)
                        <option {{$questionnaire->id == $contact->questionnaire_id ? 'selected' : ''}} value="{{$questionnaire->id}}">{{$questionnaire->skill}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="pro_stream_input_each">
                <input value="{{$contact->commission}}" placeholder="Your Commission (in %age)" type="number" class="pro_stream_input" name="pro_contact_commission" />
            </div>
            <div class="pro_stream_input_each">
                <textarea placeholder="Write your terms (if any)" type="text" class="pro_contact_textarea" name="pro_contact_terms">{{$contact->terms}}</textarea>
            </div><br><br>
            <div class="pro_m_chech_outer multi_btn flex flex-row items-center">
                <div class="flex flex-row items-center gap-2">
                    <div class="bg-white shadow-lg shadow-custom rounded-md text-sm font-semibold text-gray-600 px-5 py-2 cursor-pointer edit_with_action edit_and_send_question">
                        Save and send questionnaire
                    </div>
                    @if(!$contact->approved)
                    <div class="bg-white shadow-lg shadow-custom rounded-md text-sm font-semibold text-gray-600 px-5 py-2 cursor-pointer edit_with_action edit_and_send_agree">
                        Save and send agreement
                    </div>
                    @endif
                </div>
                <div class="bg-white shadow-lg shadow-custom rounded-md text-sm font-semibold text-gray-600 px-5 py-2 cursor-pointer ml-auto edit_now">Save</div>
            </div>
        </div>

    </div>


</form>
