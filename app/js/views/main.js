/*!
* zzChat - HTML5 Chat Application
*
* Main container view.
*
* @author Kévin Subileau
* @link https://github.com/ksubileau/zzChat
* @license GNU GPLv3 (http://www.gnu.org/licenses/gpl-3.0.html also in /LICENSE)
*/
define([
        'backbone',
        'underscore',
        'jquery',
        'views/disposable',
        'views/tab-home',
        'views/tab-room',
        'text!templates/main.html',
        'text!templates/tab-panel.html'
    ],
    function(Backbone, _, $, DisposableView, HomeTabItem, RoomTabItem, mainView, tabPanelView){
        'use strict';

		var MainView = DisposableView.extend({
		    el: $('#main'),

		    template: _.template(mainView),
		    tabPanelTemplate: _.template(tabPanelView),

            events : {
                "click #tabs li .closeTab": "closeTab",
            },

		    initialize : function() {
			    this.currentTab = null;
			    this.tabItems = [];
			    this.addTab(new HomeTabItem());
			},

		    render: function() {
				this.$el.html(this.template({}));
				this.renderTabList();
				this.showTab(this.currentTab);
				return this;
		    },

		    renderTabList: function() {
		    	$('#tabs', this.$el).html(this.tabPanelTemplate({
                    tabs: this.tabItems,
                    currentTabId: this.currentTab?this.currentTab.getId():null,
                }));
		    },

		    addTab: function(tab) {
			    this.tabItems.push(tab);
			    this.renderTabList();

			    return this;
		    },

		    getTabFromId: function (tabId) {
		    	return _(this.tabItems).findWhere({"id":tabId});
		    },

		    openRoom: function(roomId) {
		    	var tab = this.getTabFromId(roomId);
		    	if (tab) {
		    		this.showTab(tab);
		    		return;
		    	}
		    	// TODO Check room exists
		    	tab = new RoomTabItem(zzChat.rooms.get(roomId));
			    this.addTab(tab);
			    this.showTab(tab);
		    },

		    showTab: function(tab) {
		    	if (typeof tab === 'string') {
		    		tab = this.getTabFromId(tab);
		    	}

		    	if (tab) {
			    	// Remove previous tab content
			    	if(this.currentTab) {
			    		this.currentTab.dispose();
			    		this.disposed(this.currentTab);
			    	}
			    	// Set new current tab
			    	this.currentTab = tab;
			    	// Render current tab
					this.$(".tab-content").html(this.currentTab.render().$el);
					this.rendered(this.currentTab);
					// Mark tab as active
			    	$('ul li#' + tab.getId(), this.$el).addClass('active');
			    	$('ul li:not(#' + tab.getId() + ')', this.$el).removeClass('active');
			    	// Delegate Events
			    	this.currentTab.delegateEvents();
		    	}

		    	// Update route
		    	if(zzChat.router && this.currentTab) {
		    		zzChat.router.navigate(this.currentTab.getHref());
		    	}

			    return this;
		    },

		    closeTab: function(e) {
		    	e.preventDefault();
		    	// Get target tab.
		    	var tabLi = $(e.currentTarget).closest("li");
		    	var tab = this.getTabFromId(tabLi.attr("id"));
		    	// If it's the current tab, navigate to another.
		    	if (this.currentTab == tab) {
		    		// Select the closest tab.
		    		var tabIndex = _(this.tabItems).indexOf(tab);
		    		if (tabIndex + 1 < _(this.tabItems).size()) {
		    			tabIndex++;
		    		}
		    		else {
		    			tabIndex--;
		    		}
		    		this.showTab(this.tabItems[tabIndex]);
		    	}
		    	// Remove tab from collection
                this.tabItems = _(this.tabItems).without(tab);
                // Render tabs list.
                this.renderTabList();
		    },
		});
		return MainView;
    }
);