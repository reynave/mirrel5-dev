import { Component, OnInit } from '@angular/core';
import { Location } from '@angular/common';
import { ActivatedRoute, Router } from '@angular/router';
import { HttpClient } from '@angular/common/http';
import { WidgetSection } from './../widget';
import { ConfigService } from './../../service/config.service';

declare var $: any;

@Component({
  selector: 'app-widget-section',
  templateUrl: './widget-section.component.html',
  styleUrls: ['./widget-section.component.css']
})
export class WidgetSectionComponent implements OnInit {
  loading: boolean = false;
  id: string;
  section: string;
  note: string;
  items: any = [];

  constructor(
    private location: Location,
    private http: HttpClient,
    private activatedRoute: ActivatedRoute,
    private router: Router,
    private configService: ConfigService
  ) { }

  ngOnInit() {
    this.section = this.activatedRoute.snapshot.params.section;
    this.httpGet(this.section);
    this.sortable();
  }

  sortable() {
    var self = this;
    $(".sortable").sortable({
      placeholder: 'ui-state-highlight',
      handle: ".handle",
      update: function (event, ui) {
        var order = [];
        var obj;
        $('.sortable .handle').each(function (e) {
          obj = {
            id: $(this).attr('id')
          }
          order.push(obj);
        });

        console.log(order);
        self.http.post(self.configService.api() + 'widget_sortable', {
          data: order
        }, {
          headers: self.configService.headers()
        }).subscribe(data => {
          console.log(data);
          if (data['error'] == 0) {

            self.sendChild();
          }
        },
          error => {
            console.log(error.error.text);
          }
        );


      }
    }).disableSelection();
  }

  httpGet(id) {
    this.loading = true;
    var url = this.configService.api() + 'widget_section/' + id;

    this.http.get<WidgetSection>(url, {
      headers: this.configService.headers()
    }).subscribe(data => {
      this.items = data['result'];
      console.log(data);
    }, error => {
      console.log(error.error);
      console.log(error.error.text);
    });
  }

  sendChild() {
    console.log('widget.compontent : sendChild');
    var iframe = document.getElementById('iframe-live');
    if (iframe == null) return;
    var iWindow = (<HTMLIFrameElement>iframe).contentWindow;
    iWindow.postMessage({ "function": "refresh" }, '*');
  }

  add() {
    var data = {
      section: this.section
    }
    console.log(data);
    this.http.post(this.configService.api() + 'widget_insert', {
      data: data
    }, {
      headers: this.configService.headers()
    }).subscribe(data => {
      console.log(data);
      if (data['error'] == 0) {
        this.httpGet(this.section);
        this.sendChild();
      }
    },
      error => {
        console.log(error.error);
        console.log(error.error.text);
      }
    );
  }

  delete(id) {
    if (confirm('delete this widget '+id)) {


      this.loading = true;
      this.http.post(this.configService.api() + 'widget_delete', {
        id: id
      }, {
        headers: this.configService.headers()
      }).subscribe(data => {
        console.log(data);
        this.loading = false;
        this.httpGet(this.section);
        this.sendChild();
      },
        error => {
          console.log(error.error);
          console.log(error.error.text);
        }
      );
    }
  }
  detail(id) {
    this.router.navigate(['widget', id]);
  }
}
