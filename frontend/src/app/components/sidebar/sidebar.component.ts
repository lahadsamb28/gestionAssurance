import { Component, OnInit } from '@angular/core';
import { UserServiceService } from 'src/app/services/user-service.service';

@Component({
  selector: 'app-sidebar',
  templateUrl: './sidebar.component.html',
  styleUrls: ['./sidebar.component.scss']
})
export class SidebarComponent implements OnInit{
  collapsed= false;
  isLogged!: boolean;

  constructor(private controlService: UserServiceService){}

  ngOnInit(): void {
    this.controlService.status().subscribe((statusLogged: boolean) => {
      this.isLogged = statusLogged;
    })

  }

  onCollapse(){
    this.collapsed = !this.collapsed;
  }
  onHold(){
    this.collapsed = false;
  }
}
