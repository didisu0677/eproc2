captcha_image_audioObj = new SecurimageAudio({ audioElement: 'captcha_image_audio', controlsElement: 'captcha_image_audio_controls' });
function badan_usaha() {
    $('#nama').closest('.form-group').children('.col-form-label').text($('#nama').attr('data-alias1'));
    $('#npwp').closest('.form-group').children('.col-form-label').text($('#npwp').attr('data-alias1'));
    $('#no_identitas').closest('.form-group').addClass('hidden');
    $('#no_identitas,#tanggal_berakhir_identitas').val('');
    $('input[name="validation_no_identitas"],input[name="validation_tanggal_berakhir_identitas"]').val('');
    $('input[name="validation_id_bentuk_badan_usaha"],input[name="validation_id_status_perusahaan"]').val('required');
    $('#id_bentuk_badan_usaha,#id_status_perusahaan').closest('.form-group').removeClass('hidden');
    $('#label-alamat').text($('#label-alamat').attr('data-alias1'));
}
function perorangan() {
    $('#nama').closest('.form-group').children('.col-form-label').text($('#nama').attr('data-alias2'));
    $('#npwp').closest('.form-group').children('.col-form-label').text($('#npwp').attr('data-alias2'));
    $('#no_identitas').closest('.form-group').removeClass('hidden');
    $('#id_bentuk_badan_usaha,#id_status_perusahaan').closest('.form-group').addClass('hidden');
    $('#id_bentuk_badan_usaha,#id_status_perusahaan').val('').trigger('change');
    $('input[name="validation_id_bentuk_badan_usaha"],input[name="validation_id_status_perusahaan"]').val('');
    $('input[name="validation_no_identitas"],input[name="validation_tanggal_berakhir_identitas"]').val('required');
    $('#label-alamat').text($('#label-alamat').attr('data-alias2'));
}
function checkbox_setuju() {
    setTimeout(function(){
        if($('#setuju').is(':checked')) {
            $('button[type="submit"]').removeAttr('disabled');
        } else {
            $('button[type="submit"]').attr('disabled',true);
        }
    },100);
}
function toHome() {
    window.location = base_url;
}
$('.select2').each(function(){
    var $t = $(this);
    $t.select2({
        placeholder: ''
    });
});
$('.dp').each(function(){
    var placeholder = typeof $(this).attr('placeholder') != 'undefined' ? $(this).attr('placeholder') : 'dd/mm/yyyy';
    $(this).mask('00/00/0000', {placeholder: placeholder});
});
$('.dp').daterangepicker({
    singleDatePicker: true,
    showDropdowns: true,
    minYear: 1950,
    maxYear: parseInt(moment().format('YYYY'),10) + 3,
    locale: {
        format: 'DD/MM/YYYY',
        cancelLabel: lang.batal,
        applyLabel: lang.ok,
        daysOfWeek: [lang.sen, lang.sel, lang.rab, lang.kam, lang.jum, lang.sab, lang.min],
        monthNames: [lang.jan, lang.feb, lang.mar, lang.apr, lang.mei, lang.jun, lang.jul, lang.agu, lang.sep, lang.okt, lang.nov, lang.des]
    },
    autoUpdateInput: false
}, function(start, end, label) {
    $(this.element[0]).removeClass('is-invalid');
    $(this.element[0]).parent().find('.error').remove();
}).on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('DD/MM/YYYY'));
    var act = window[$(this).attr('id') + '_callback'];
    if(typeof act == 'function') {
        act();
    }
}).on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('');
    var act = window[$(this).attr('id') + '_callback'];
    if(typeof act == 'function') {
        act();
    }
});
$('#jenis_rekanan, .jenis_rekanan').click(function(){
    badan_usaha();
});
$('#jenis_rekanan_1, .jenis_rekanan_1').click(function(){
    perorangan();
});
$(document).ready(function(){
    badan_usaha();
    $('#form-reg button[type="submit"]').attr('disabled',true).addClass('no-spinner');
});
$('#id_negara').change(function(){
    if($(this).val() != '101') {
        $('#id_provinsi').html('<option value=""></option><option value="999">'+lang.lainnya+'</option>').trigger('change');
    } else {
        $('#id_provinsi').html('<option value="0">'+lang.mohon_tunggu+'</option>').trigger('change');
        readonly_ajax = false;
        $.getJSON(base_url + 'ajax/json/wilayah', function(data){
            var konten = '<option value=""></option>';
            $.each(data,function(d,v){
                konten += '<option value="'+v.id+'">'+v.nama+'</option>';
            });
            konten += '<option value="999">'+lang.lainnya+'</option>';
            $('#id_provinsi').html(konten).trigger('change');
            readonly_ajax = true;
            checkbox_setuju();
        });
    }
});
$('#id_provinsi').change(function(){
    if($(this).val() != '' && $(this).val() != '0') {
        if($(this).val() == '999') {
            $('#nama_provinsi').parent().removeClass('hidden');
            $('#nama_provinsi').val('');
            $('#id_kota').html('<option value=""></option><option value="999">'+lang.lainnya+'</option>').trigger('change');
        } else {
            $('#nama_provinsi').parent().addClass('hidden');
            $('#nama_provinsi').val($(this).find(':selected').text());
            $('#id_kota').html('<option value="0">'+lang.mohon_tunggu+'</option>').trigger('change');
            readonly_ajax = false;
            $.getJSON(base_url + 'ajax/json/wilayah/' + $(this).val(), function(data){
                var konten = '<option value=""></option>';
                $.each(data,function(d,v){
                    konten += '<option value="'+v.id+'">'+v.nama+'</option>';
                });
                konten += '<option value="999">'+lang.lainnya+'</option>';
                $('#id_kota').html(konten).trigger('change');
                readonly_ajax = true;
                checkbox_setuju();
            });
        }
    } else {
        $('#nama_provinsi').parent().addClass('hidden');
        $('#nama_provinsi').val($(this).find(':selected').text());
        $('#id_kota').html('<option value=""></option>').trigger('change');
    }
});
$('#id_kota').change(function(){
    if($(this).val() != '' && $(this).val() != '0') {
        if($(this).val() == '999') {
            $('#nama_kota').parent().removeClass('hidden');
            $('#nama_kota').val('');
            $('#id_kecamatan').html('<option value=""></option><option value="999">'+lang.lainnya+'</option>').trigger('change');
        } else {
            $('#nama_kota').parent().addClass('hidden');
            $('#nama_kota').val($(this).find(':selected').text());
            $('#id_kecamatan').html('<option value="0">'+lang.mohon_tunggu+'</option>').trigger('change');
            readonly_ajax = false;
            $.getJSON(base_url + 'ajax/json/wilayah/' + $(this).val(), function(data){
                var konten = '<option value=""></option>';
                $.each(data,function(d,v){
                    konten += '<option value="'+v.id+'">'+v.nama+'</option>';
                });
                konten += '<option value="999">'+lang.lainnya+'</option>';
                $('#id_kecamatan').html(konten).trigger('change');
                readonly_ajax = true;
                checkbox_setuju();
            });
        }
    } else {
        $('#nama_kota').parent().addClass('hidden');
        $('#nama_kota').val($(this).find(':selected').text());
        $('#id_kecamatan').html('<option value=""></option>').trigger('change');
    }
});
$('#id_kecamatan').change(function(){
    if($(this).val() != '' && $(this).val() != '0') {
        if($(this).val() == '999') {
            $('#nama_kecamatan').parent().removeClass('hidden');
            $('#nama_kecamatan').val('');
            $('#id_kelurahan').html('<option value=""></option><option value="999">'+lang.lainnya+'</option>').trigger('change');
        } else {
            $('#nama_kecamatan').parent().addClass('hidden');
            $('#nama_kecamatan').val($(this).find(':selected').text());
            $('#id_kelurahan').html('<option value="0">'+lang.mohon_tunggu+'</option>').trigger('change');
            readonly_ajax = false;
            $.getJSON(base_url + 'ajax/json/wilayah/' + $(this).val(), function(data){
                var konten = '<option value=""></option>';
                $.each(data,function(d,v){
                    konten += '<option value="'+v.id+'">'+v.nama+'</option>';
                });
                konten += '<option value="999">'+lang.lainnya+'</option>';
                $('#id_kelurahan').html(konten).trigger('change');
                readonly_ajax = true;
                checkbox_setuju();
            });
        }
    } else {
        $('#nama_kecamatan').parent().addClass('hidden');
        $('#nama_kecamatan').val($(this).find(':selected').text());
        $('#id_kelurahan').html('<option value=""></option>').trigger('change');
    }
});
$('#id_kelurahan').change(function(){
    if($(this).val() == '999') {
        $('#nama_kelurahan').parent().removeClass('hidden');
        $('#nama_kelurahan').val('');
    } else {
        $('#nama_kelurahan').parent().addClass('hidden');
        $('#nama_kelurahan').val($(this).find(':selected').text());
    }
});
$('#setuju').click(function(){
    if($(this).is(':checked')) {
        $('button[type="submit"]').removeAttr('disabled');
    } else {
        $('button[type="submit"]').attr('disabled',true);
    }
});
$('#form-reg').submit(function(e){
    e.preventDefault();
    if(validation('form-reg')) {
        $.ajax({
            url : $(this).attr('action'),
            data : $(this).serialize(),
            type : 'post',
            dataType: 'json',
            success : function(response) {
                if(response.status == 'success') {
                    cAlert.open(response.message,response.status,'toHome');
                } else {
                    cAlert.open(response.message,response.status);
                    $('#captcha_refresh').trigger('click');
                    $('#captcha_code').val('');
                }
            }
        });
    }
});
