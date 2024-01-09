import { Component, OnInit } from '@angular/core';
import { CustomvalidationService } from './services/customvalidation.service';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit{
  title = 'frontend';
  visible: any;
  constructor(private nav: CustomvalidationService){}
  ngOnInit(): void {
    this.nav.checkBarVisibility().subscribe((visibility: boolean) => {
      Promise.resolve().then(() => this.visible = visibility)
    })
  }
}
