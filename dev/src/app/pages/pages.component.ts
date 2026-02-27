import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { HttpClient } from '@angular/common/http';
import { ConfigService } from './../service/config.service';
import { Pages } from './pages';

declare var $: any;
@Component({
  selector: 'app-pages',
  templateUrl: './pages.component.html',
  styleUrls: ['./pages.component.css']
})
export class PagesComponent implements OnInit {
  loading: boolean = false;
  items: any = [];
  itemsChild: any = [];
  benchmark: any = [];
  current: string = " / ";
  id: number;
  childHeader: string;

  constructor(
    private http: HttpClient,
    private activatedRoute: ActivatedRoute,
    private router: Router,
    private configService: ConfigService,
  ) { }

  ngOnInit() {
    this.id = this.activatedRoute.snapshot.params.id;
    this.sortable();
    this.httpGet(this.id);
  }


  sendChild(url) {
    console.log('pages.compontent : sendChild');
    var iframe = document.getElementById('iframe-live');
    if (iframe == null) return;
    var iWindow = (<HTMLIFrameElement>iframe).contentWindow;
    if (url == 'null') {
      iWindow.postMessage({ "function": "redirect" }, '*');
    } else {
      iWindow.postMessage({ "function": "redirect", "data": url }, '*');
    }
  }


  sortable() {
    var self = this;
    $(".sortable").sortable({
      placeholder: 'ui-state-highlight',
      handle: ".handle",
      update: function (event, ui) {
        var order = [];
        var obj;
        $('.sortable .ui-state-default').each(function (e) {
          obj = {
            id: $(this).attr('id'),
            sorting: $(this).index() + 1
          }
          order.push(obj);
        });

        //   console.log(order);
        self.http.post(self.configService.api() + 'pages_sortable', {
          data: order
        }, {
          headers: self.configService.headers()
        }).subscribe(data => {
          console.log(data);
          self.sendChild('null');
        },
          error => {
            console.log(error.error.text);
          }
        );


      }
    }).disableSelection();

  }

  httpGet(id_parent) {

    if (id_parent == 'undefined') {
      id_parent = 0;
    }
    this.loading = true;
    var url = this.configService.api() + 'pages/' + id_parent;
    this.http.get<Pages>(url, {
      headers: this.configService.headers()
    }).subscribe(data => {
      console.log(data)
      this.items = data['pages'];
      this.loading = false;
      this.benchmark = data['benchmark'];
    }, error => {
      console.log(error.error);
      console.log(error.error.text);
    });
  }

  root() {
    this.httpGet('0');
    this.router.navigate(['pages/0']);
    this.id = 0;
    this.childHeader = "";
    this.itemsChild = [];
    this.current = "";
  }

  status(value, x) {
    console.log(value, x);
    var objIndex = this.items.findIndex((obj => obj.id == x.id));
    this.items[objIndex]['status'] = value;

    this.http.post(this.configService.api() + 'pages_status', {
      id: x.id,
      status: value
    }, {
      headers: this.configService.headers()
    }).subscribe(data => {
      this.loading = false;
    },
      error => {
        console.log(error.error.text);
      }
    );

  }

  statusChild(value, x) {
    var objIndex = this.items.findIndex((obj => obj.id == this.id));
    var childIndex = this.items[objIndex]['children'].findIndex(obj => obj.id == x.id);
    this.items[objIndex]['children'][childIndex]['status'] = value;


    this.http.post(this.configService.api() + 'pages_status', {
      id: x.id,
      status: value
    }, {
      headers: this.configService.headers()
    }).subscribe(data => {
      this.loading = false;
    },
      error => {
        console.log(error.error.text);
      }
    );

  }

  child(x) {
    this.id = x.id;
    console.log(x);
    this.current = ' / ' + x.name;
    this.childHeader = x.name;
    this.itemsChild = x.children;
  }

  grandChild(x) {
    this.current = '';
    this.router.navigate(['pages/', x.id_pages]);
    this.httpGet(x.id_pages);
    this.itemsChild = [];
  }

  delete(x) {
    if (confirm('Delete this pages ' + x.name)) {

      var self = this;
      $("#" + x.id).hide("slow", function () {
        var objIndex = self.items.findIndex((obj => obj.id == x.id));
        self.items.splice(objIndex, 1);
      });

      this.http.post(this.configService.api() + 'pages_delete', {
        id: x.id
      }, {
        headers: this.configService.headers()
      }).subscribe(data => {
        this.loading = false;
      },
        error => {
          console.log(error.error.text);
        }
      );



    }
  }

  deleteChild(x) {

    if (confirm('Delete this pages ' + x.name)) {

      var self = this;
      $("#" + x.id).hide("slow", function () {
        var objIndex = self.items.findIndex((obj => obj.id == self.id));
        var childIndex = self.items[objIndex]['children'].findIndex(obj => obj.id == x.id);
        self.items[objIndex]['children'].splice(childIndex, 1)
      });
      this.http.post(this.configService.api() + 'pages_delete', {
        id: x.id
      }, {
        headers: this.configService.headers()
      }).subscribe(data => {
        this.loading = false;
      },
        error => {
          console.log(error.error.text);
        }
      );
    }
  }

  addPages() {
    console.log('addPages');
    this.loading = true;
    this.http.post(this.configService.api() + 'pages_insert', {
      id_pages: this.activatedRoute.snapshot.params.id ? this.activatedRoute.snapshot.params.id : '0'
    }, {
      headers: this.configService.headers()
    }).subscribe(data => {
      console.log(data);
      this.loading = false;
      this.items.push(data['result']['data']);
    },
      error => {
        console.log(error.error.text);
      }
    );


  }

  addChild() {
    console.log(this.id);
    console.log('addPages');
    this.loading = true;
    this.http.post(this.configService.api() + 'pages_addChild', {
      id_pages: this.id
    }, {
      headers: this.configService.headers()
    }).subscribe(data => {
      console.log(data);
      this.loading = false;
      if (data['error'] == 0) {
        var objIndex = this.items.findIndex((obj => obj.id == this.id));
        this.items[objIndex]['child'] = true;
        this.items[objIndex]['children'].push(data['result']['data']);
      }
      // this.items.push(data['result']['data']);
    },
      error => {
        console.log(error.error.text);
      }
    );
  }
}
