import { Component, OnDestroy, HostListener } from '@angular/core';
import { CommonModule } from '@angular/common';
import { DataService } from 'src/app/data.service';
import { Subscription } from 'rxjs';
import { RouterModule } from '@angular/router';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-sitebar-wrapper',
  standalone: true,
  imports: [CommonModule, RouterModule, FormsModule],
  templateUrl: './sitebar-wrapper.component.html',
  styleUrls: ['./sitebar-wrapper.component.css']
})
export class SitebarWrapperComponent implements OnDestroy {

  public screenWidth: any;
  public screenHeight: any;
  data: any;
  class: any;
  myObs: Subscription;

  innerStyle: string = 'width: 240px!important';
  topStyle: string = 'width: 300px!important';
  fontStyle="font-size: 16px!important;"

  ngOnDestroy() {
      this.myObs.unsubscribe();
  }

  ngOnInit() {
      this.screenWidth = window.innerWidth;
      this.screenHeight = window.innerHeight;
      this.innerStyle = 'width: 240px!important';
      this.topStyle = 'width: 300px!important';
      this.fontStyle="font-size: 16px!important;"      
      if (this.screenWidth<=1746) {
        this.innerStyle='width: 240px!important;';
        this.topStyle='width: 260px!important; margin-left:-20px;';
      }
      if (this.screenWidth<=1538) {
        this.innerStyle='width: 220px!important;';
        this.topStyle='width: 240px!important; margin-left:-20px;';
        this.fontStyle="font-size: 14px!important;"        
      }         
      if (this.screenWidth<=1281) {
        this.innerStyle='width: 200px!important;';
        this.topStyle='width: 220px!important; margin-left:-20px;';
        this.fontStyle="font-size: 12px!important;"       
      }      

  }
  
  @HostListener('window:resize', ['$event'])
  onWindowResize() {
    this.screenWidth = window.innerWidth;
    this.screenHeight = window.innerHeight;
    if (this.screenWidth < 1098) {

    }
  }

  constructor(private _dataService: DataService) { 
    this.myObs = this._dataService.locationSubject.subscribe(d => {
      this.data=d;
      if (this.data.hideNav=='Y') {
        this.class="";
      } else {
        this.class="";
      }
    })
  }

}
