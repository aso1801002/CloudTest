@extends('layouts.ctc')

@section('back_button')
<a class="navbar-brand" href="{{route('top.top')}}">＜</a>
@endsection

@section('nav_title','カプセルプロフィール')

@section('content')

<head>
     <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
</head>

@if (session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif

<div>

	<a href="#" class="thum">
		@if($capsule_data->thumbnail == 'noImage.png')
			<img src="/noImage.png" loading="lazy" class="thum__img">
		@else
			<img src="{{ $capsule_data->thumbnail }}" loading="lazy" class="thum__img">
		@endif
		<div class="thum__title">
			{{$capsule_data->name}}(id:{{$capsule_data->id}})<br>
			開封予定日 : {{$capsule_data->open_date_str}}
		</div>
	</a>

	<div><!-- 概要 -->
		<hr>
		{{$capsule_data->intro}}
		<hr>
	</div>



	<!-- 追加ボタンor開封ボタンの有無 -->

	@if( $open_flag == 1 )
		<form method="POST" action="{{ route('image.index') }}">
		@csrf
		@if( $capsule_data->lat == null)
			<input type = "hidden" name = "capsule_id" value = "{{$capsule_data->id}}">
			<input type="submit" class="btn-warning btn-block p-3 text-center waves-effect" style="border-radius:15px;" name="add" value="開封する">
			
		@else
			<input type = "hidden" name = "capsule_id" value = "{{$capsule_data->id}}">
			<input type = "submit" name = "add" value="+">
			<input type = "hidden" id = "lat" name = "lat" value = "">
			<input type = "hidden" id = "lng"  name = "lng" value = "">
			<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyANCqtHnmILMQAAIBMx0KLYKRwxZVOu96o&callback=initMap"></script>
			<script src="{{ asset('/js/futachi.js') }}"></script>
			開封する
		@endif
		</form>
	@elseif( $open_flag == 0 )
		<div id="modalActivate" class="btn-primary p-1 text-center waves-effect p-4" data-toggle="modal" data-target="#modalPreview0"  style="border-radius: 15px;">
			写真を追加
		</div>
	@else
	open_flagの値が適切ではありません。
	@endif

	<!-- 追加ボタンor開封ボタン -->

	<div class="card p-3 mt-2">
		最近の投稿<hr>
		<font color="success">
			<a href="/chat/{{$capsule_data->id}}">{{ $first_chat -> message}}</a>
		</font>
	</div>
	<a href="/member_list/{{$capsule_data->id}}">
		<div class="card p-3 mt-2">
			メンバー一覧
		</div>
	</a>
	<div class="card p-3 mt-2">
		招待する
		<hr>
		<div class="row">
			<div class="col-5">
				招待コード :<br>
				{{ $capsule_data->entry_code }}
			</div>
			<div class="col-7">
				<a href="/member_add_select/{{$capsule_data->id}}">
					<div class="btn btn-primary text-center"  style="border-radius: 15px;">
						直接招待
					</div>
				</a>
			</div>
		</div>
		
	</div>

	<!-- カプセル破棄ボタンの有無 -->

	@if( $admin_flag == 1 )
	<a href="/capsule_edit/{{$capsule_data->id}}">
		<div class="btn-primary m-2 p-3 text-center waves-effect" style="border-radius:15px;">
			タイムカプセルを編集
		</div>
	</a>
	<div class="btn-danger m-2 p-3 text-center waves-effect" data-toggle="modal" data-target="#modalPreview1" style="border-radius:15px;">
		タイムカプセルを破棄
	</div>
	@elseif( $admin_flag == 0 )
	<div class="btn-danger m-2 p-3 text-center waves-effect" data-toggle="modal" data-target="#modalPreview2" style="border-radius: 15px;">
		タイムカプセルから脱退
	</div>
	@else
	※admin_flagの値が適切ではありません。※
	@endif

	<!-- カプセル破棄ボタンの有無 -->
</div>

<!-- 思い出追加ポップアップ -->
<form method="POST" action="{{ route('image.store') }}" enctype="multipart/form-data">
    @csrf
	<div class="modal fade right" id="modalPreview0" tabindex="-1" role="dialog" aria-labelledby="modalPreviewLabel0" aria-hidden="true" style="color:black;">
		<div class="modal-dialog" role="document">
			<div class="modal-content">

				<div class="modal-header">
					<h3 class="modal-title" id="modalPreviewLabel0">思い出を追加します</h3>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				
				<div class="tabs">
					<input id="all" type="radio" name="tab_item" checked>
					<label class="tab_item" for="all">写真</label>
					<input id="programming" type="radio" name="tab_item">
					<label class="tab_item" for="programming">手紙</label>
					<div class="tab_content" id="all_content">
						<div class="tab_content_description">
							<div class="modal-body" style="padding:40px;">
								<h6 class="pb-2">・写真を選択し、思い出をアップロードしてください。<br></h6>
								<h7>タイトル:</h7>
								<input type="text" name="title" placeholder="(例)あの頃の一枚">
								<div class="md-form">
									<img id="img_prv" src="{{ asset('/noImage.png') }}">
									<input id="image" type="file" name="image" accept=".png,.jpg,.jpeg,image/png,image/jpg"><br>
								</div>
								<input type="hidden" name = "capsule_id" value="{{$capsule_data->id}}">
							</div>
						</div>
					</div>
					<div class="tab_content" id="programming_content">
						<div class="tab_content_description">
							<div class="modal-body" style="padding:40px;">
								<h6 class="pb-2">・手紙を書き、思い出をアップロードしてください。<br></h6>
								<h7>タイトル:</h7>
								<input type="text" name="title" placeholder="(例)あの時の君へ">
								<div class="md-form">
									<textarea id="letter" type="text" name="letter" rows="6" cols="50"></textarea><br>
								</div>
								<input type="hidden" name = "capsule_id" value="{{$capsule_data->id}}">
							</div>
						</div>
					</div>
				</div>
				
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary btn-block waves-effect p-3" style="border-radius:15px;">アップロード</button>
				</div>

			</div>
		</div>
	</div>
</form>
<!-- 思い出追加ポップアップ -->

<!-- 破棄前ポップアップ -->
<form method="POST" action="{{ url('/capsule_delete') }}" enctype="multipart/form-data">
    @csrf
	<input type="hidden" name ="capsule_id" value="{{$capsule_data->id}}">

	<div class="modal fade right" id="modalPreview1" tabindex="-1" role="dialog" aria-labelledby="modalPreviewLabel1" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title" id="modalPreviewLabel1" style="color:black;">タイムカプセルを破棄</h3>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" style="padding:40px;">
					<h6 class="pb-5" style="color:black;">□このカプセルを削除します。この操作は取り消せません。<br><br>※この操作を実行すると、カプセルとその中身、チャットも完全に削除されます。</h6>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-danger btn-block waves-effect" style="border-radius:15px;">OK</button>
				</div>
			</div>
		</div>
	</div>

</form>
<!-- 破棄前ポップアップ- -->

<!-- 破棄前ポップアップ -->
<form method="POST" action="{{ url('/capsule_exit') }}" enctype="multipart/form-data">
    @csrf
	<input type="hidden" name ="capsule_id" value="{{$capsule_data->id}}">

	<div class="modal fade right" id="modalPreview2" tabindex="-1" role="dialog" aria-labelledby="modalPreviewLabel1" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title" id="modalPreviewLabel2" style="color:black;">脱退します</h3>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" style="padding:40px;">
					<h6 class="pb-5" style="color:black;">カプセル名:<br>{{$capsule_data->name}}<br><br>□このカプセルから脱退します。この操作は取り消せません。
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-danger btn-block waves-effect" style="border-radius:15px;">OK</button>
				</div>
			</div>
		</div>
	</div>

</form>
<!-- 破棄前ポップアップ- -->





<style>

.card{
	background-image: url('/image/scale_r.jpg');
	background-size:cover;
	color:white;
}
.thum {
  display: block;
  position: relative;
  overflow: hidden;
  border-radius: 15px;
}
/* テキストをカード下に固定配置する */
.thum__title {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  padding: 13px;
  text-decoration: none;
  color: #FFF;
  font-weight: bold;
  font-size: 1.6em;
}
.thum__img {
  display: block;
  width: 100%;
  filter: brightness(70%); /* フィルター */
  transition: 0.3s; /* トランジション */
}
/* カードホバー時 */
.thum:hover .thum__img {
  filter: brightness(150%); /* フィルターを変更 */
  transform: scale(1.3); /* 画像を拡大 */
}

/*タブ切り替え全体のスタイル*/
.tabs {
  margin-top: 50px;
  padding-bottom: 40px;
  background-color: #fff;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
  width: 100%;
  margin: 0 auto;}

/*タブのスタイル*/
.tab_item {
  width: calc(100%/2);
  height: 50px;
  border-bottom: 3px solid #5ab4bd;
  background-color: #d9d9d9;
  line-height: 50px;
  font-size: 16px;
  text-align: center;
  color: #565656;
  display: block;
  float: left;
  text-align: center;
  font-weight: bold;
  transition: all 0.2s ease;
}
.tab_item:hover {
  opacity: 0.75;
}

/*ラジオボタンを全て消す*/
input[name="tab_item"] {
  display: none;
}

/*タブ切り替えの中身のスタイル*/
.tab_content {
  display: none;
  clear: both;
  overflow: hidden;
}


/*選択されているタブのコンテンツのみを表示*/
#all:checked ~ #all_content,
#programming:checked ~ #programming_content,
#design:checked ~ #design_content {
  display: block;
}

/*選択されているタブのスタイルを変える*/
.tabs input:checked + .tab_item {
  background-color: #5ab4bd;
  color: #fff;
}

</style>

<script>
//画像が選択される度に、この中の処理が走る
$('#image').on('change', function (ev) {
	//コンソールタブで適切に処理が動いているか確認
	console.log("image is changed");
	//このFileReaderが画像を読み込む上で大切
	const reader = new FileReader();
	//--ファイル名を取得
	const fileName = ev.target.files[0].name;
	//--画像が読み込まれた時の動作を記述
	reader.onload = function (ev) {
		$('#img_prv').attr('src', ev.target.result).css('width', '150px').css('height', '150px');
	}
	reader.readAsDataURL(this.files[0]);
})
</script>

@endsection
	