import { Component, OnInit } from '@angular/core';
import { Location } from '@angular/common';
import { ActivatedRoute, Router } from '@angular/router';
import { HttpClient } from '@angular/common/http';
import { fn } from '@angular/compiler/src/output/output_ast';
import { WidgetDetail, WidgetEdit } from './../widget';
import { ConfigService } from './../../service/config.service';

@Component({
  selector: 'app-widget-edit',
  templateUrl: './widget-edit.component.html',
  styleUrls: ['./widget-edit.component.css']
})
export class WidgetEditComponent implements OnInit {
  loading: boolean = false;
  model: any = [];
  id: string;
  selectedFile: File = null;
  img: string;
  note: string;
  section:string;
  initTinymce : any = {
    plugins: 'link',
    toolbar:false,
    branding: false
  }
  initInline : any= {
    plugins: ['quickbars', 'save','link'],
    quickbars_selection_toolbar: 'bold italic underline link',
    menubar: false,
    toolbar: "save undo redo link",
    inline: true,
  }

  label:any = {
    h1:"Text 1",
    h2:"Text 2",
    h3:"Text 3",
    h4:"Text 4",
    content:"Content", 
    img:"Images"
  } 


  constructor(
    private location: Location,
    private http: HttpClient,
    private activatedRoute: ActivatedRoute,
    private router: Router,
    private configService: ConfigService
  ) { }

  ngOnInit() {
    this.id = this.activatedRoute.snapshot.params.id; 
    this.httpGet(this.id); 

    if(localStorage.getItem('widget') ){
      var obj = JSON.parse(localStorage.getItem('widget'));
      this.label  = {
        h1: obj['h1'] ? obj['h1'] : false,
        h2: obj['h2'] ? obj['h2'] : false, 
        h3: obj['h3'] ? obj['h3'] : false, 
        h4: obj['h4'] ? obj['h4'] : false, 
        content: obj['content'] ? obj['content'] : false, 
        img: obj['img'] ? obj['img'] : false, 
        
      } 
      console.log( this.label );
    } 
    if(localStorage.getItem('galleries') ){
      var obj = JSON.parse(localStorage.getItem('galleries'));
      this.label  = {
        h1: obj['h1'] ? obj['h1'] : false,
        h2: obj['h2'] ? obj['h2'] : false, 
        h3: obj['h3'] ? obj['h3'] : false, 
        h4: obj['h4'] ? obj['h4'] : false, 
        content: obj['content'] ? obj['content'] : false, 
        img: obj['img'] ? obj['img'] : false, 
        
      } 
      console.log( this.label );
    } 
  }

  httpGet(id) {
    this.loading = true;
    var url = this.configService.api() + 'widget_detail/' + id;
 
    this.http.get<WidgetDetail>(url, {
      headers: this.configService.headers()
    }).subscribe(data => {
      
      this.loading = false;
      this.model = new WidgetEdit(
        data['result']['data']['h1'],
        data['result']['data']['h2'],
        data['result']['data']['h3'],
        data['result']['data']['h4'],
        data['result']['data']['content'],  
        data['result']['data']['img'],  
        data['result']['data']['href'],  
        
      );
      this.img = data['result']['data']['img'];
     
        
      this.section =  data['result']['data']['section'];  
    }, error => {
      console.log(error.error);
      console.log(error.error.text);
    });
  }

  onSubmit() { 
    console.log(this.id,this.model);
    this.http.post(this.configService.api() + 'widget_update', {
      id: this.id,
      data: this.model
    }, {
      headers: this.configService.headers()
    }).subscribe(data => {
      this.loading = false;
      if (data['error'] == 0) { 
        this.note = "Update done";
        this.sendChild();
      }
    },
      error => {
        console.log(error.error.text);
      }
    );

  }

  sendChild() {
    console.log('widget.compontent : sendChild'); 
    var iframe = document.getElementById('iframe-live');
    if (iframe == null) return;
    var iWindow = (<HTMLIFrameElement>iframe).contentWindow; 
    iWindow.postMessage({ "function":"refresh" }, '*');
  }

 
  back() {
    this.router.navigate(['widget/section',this.section]);
    //this.location.back();
  }


  upload: string;
  onFileSelected(event) {
    if (<File>event.target.files[0]) {


      this.upload = 'uploading...';
      this.model['img'] = this.configService.base_url() + 'admin/img/Ripple-1s-200px.svg';
      this.selectedFile = <File>event.target.files[0];
      const fd = new FormData();
      fd.append('images', this.selectedFile, this.selectedFile.name);
      fd.append('id', this.id);

      this.http.post(this.configService.api() + 'widget_upload/', fd, {
        headers: this.configService.headers()
      })
        .subscribe(res => {
          if (res['error']) {
            alert(res['error']);
          } else {
            this.model['img'] = res['img'];
            this.img = res['img'];
            
          }
          this.upload = '';
        },
          error => { console.log(error); }
        );
    }
  }

}
