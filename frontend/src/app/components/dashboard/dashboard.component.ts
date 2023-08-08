import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { CustomvalidationService } from 'src/app/services/customvalidation.service';
import { UserServiceService } from 'src/app/services/user-service.service';

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.scss']
})
export class DashboardComponent implements OnInit{

  constructor(private userService: UserServiceService, private router: Router, private passMessage: CustomvalidationService){}

  ngOnInit(): void {
  }


}
